<?php

namespace App\Services\Redis;

use App\Models\Driver;
use App\Models\Inspector;
use App\Models\Insurance;
use App\Models\Inspections;
use App\Models\Maintenance;
use App\Models\Manager;
use App\Models\Role;
use App\Models\Route;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;

class RedisFleetSnapshotService
{
    private const DATASETS = [
        'users' => ['model' => User::class, 'primary_key' => 'id'],
        'roles' => ['model' => Role::class, 'primary_key' => 'role_id'],
        'drivers' => ['model' => Driver::class, 'primary_key' => 'driver_id'],
        'inspectors' => ['model' => Inspector::class, 'primary_key' => 'goverment_id'],
        'managers' => ['model' => Manager::class, 'primary_key' => 'manager_id'],
        'routes' => ['model' => Route::class, 'primary_key' => 'route_id'],
        'vehicles' => ['model' => Vehicle::class, 'primary_key' => 'vehicle_id'],
        'inspections' => ['model' => Inspections::class, 'primary_key' => 'inspection_id'],
        'insurance' => ['model' => Insurance::class, 'primary_key' => 'insurance_id'],
        'maintenance' => ['model' => Maintenance::class, 'primary_key' => 'maintenance_id'],
    ];

    public function isConfigured(): bool
    {
        return array_key_exists($this->connectionName(), config('database.redis', []));
    }

    public function sync(bool $flush = false): array
    {
        $redis = Redis::connection($this->connectionName());
        $manifestKey = $this->manifestKey();

        if ($flush) {
            $this->flushPreviousSnapshot($redis);
        }

        $manifest = [
            'synced_at' => now()->toIso8601String(),
            'redis_connection' => $this->connectionName(),
            'keys' => [$manifestKey],
            'datasets' => [],
            'migrations' => [],
        ];

        foreach (self::DATASETS as $dataset => $definition) {
            $records = $definition['model']::query()
                ->orderBy($definition['primary_key'])
                ->get()
                ->map(fn ($model) => $model->toArray())
                ->values()
                ->all();

            $datasetKeys = $this->storeDatasetSnapshot($redis, $dataset, $definition['primary_key'], $records);

            $manifest['keys'] = array_values(array_unique([...$manifest['keys'], ...$datasetKeys]));
            $manifest['datasets'][$dataset] = [
                'count' => count($records),
                'primary_key' => $definition['primary_key'],
            ];
        }

        $diskMigrationKey = $this->keyFor('migrations:disk');
        $diskMigrations = collect(File::files(database_path('migrations')))
            ->map(fn ($file) => $file->getFilename())
            ->sort()
            ->values()
            ->all();

        $redis->set($diskMigrationKey, $this->encode($diskMigrations));

        $manifest['keys'][] = $diskMigrationKey;
        $manifest['migrations']['disk'] = [
            'count' => count($diskMigrations),
        ];

        foreach ($this->migrationConnections() as $connectionName) {
            $appliedMigrationKey = $this->keyFor("migrations:applied:{$connectionName}");
            $appliedMigrations = $this->appliedMigrations($connectionName);

            $redis->set($appliedMigrationKey, $this->encode($appliedMigrations));

            $manifest['keys'][] = $appliedMigrationKey;
            $manifest['migrations'][$connectionName] = [
                'count' => count($appliedMigrations),
            ];
        }

        $manifest['keys'] = array_values(array_unique($manifest['keys']));

        $redis->set($manifestKey, $this->encode($manifest));

        return $manifest;
    }

    private function storeDatasetSnapshot($redis, string $dataset, string $primaryKey, array $records): array
    {
        $keys = [];
        $allRecordsKey = $this->keyFor("records:{$dataset}:all");

        $redis->set($allRecordsKey, $this->encode($records));
        $keys[] = $allRecordsKey;

        foreach ($records as $record) {
            if (! array_key_exists($primaryKey, $record) || $record[$primaryKey] === null) {
                continue;
            }

            $recordKey = $this->keyFor("records:{$dataset}:{$record[$primaryKey]}");

            $redis->set($recordKey, $this->encode($record));
            $keys[] = $recordKey;
        }

        return $keys;
    }

    private function flushPreviousSnapshot($redis): void
    {
        $existingManifest = json_decode((string) $redis->get($this->manifestKey()), true);
        $keys = collect($existingManifest['keys'] ?? [])
            ->filter(fn ($key) => is_string($key) && $key !== '')
            ->values()
            ->all();

        if ($keys !== []) {
            $redis->del(...$keys);
        }
    }

    private function appliedMigrations(string $connectionName): array
    {
        if (! array_key_exists($connectionName, config('database.connections', []))) {
            return [];
        }

        $migrationTable = config('database.migrations.table', 'migrations');

        if (! Schema::connection($connectionName)->hasTable($migrationTable)) {
            return [];
        }

        return DB::connection($connectionName)
            ->table($migrationTable)
            ->orderBy('batch')
            ->orderBy('migration')
            ->get(['migration', 'batch'])
            ->map(fn ($migration) => [
                'migration' => $migration->migration,
                'batch' => (int) $migration->batch,
            ])
            ->all();
    }

    private function migrationConnections(): array
    {
        $configuredConnections = array_keys(config('database.connections', []));
        $connections = array_filter([
            config('database.default'),
            'supabase',
        ]);

        return array_values(array_unique(array_filter(
            $connections,
            fn ($connection) => in_array($connection, $configuredConnections, true)
        )));
    }

    private function connectionName(): string
    {
        return (string) config('database.redis_sync.connection', 'fleet');
    }

    private function manifestKey(): string
    {
        return $this->keyFor('manifest');
    }

    private function keyFor(string $suffix): string
    {
        return config('database.redis_sync.prefix', 'matutu_system_fleet').':'.$suffix;
    }

    private function encode(array $payload): string
    {
        return (string) json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
