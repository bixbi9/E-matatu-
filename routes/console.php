<?php

use App\Services\Redis\RedisFleetSnapshotService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('supabase:migrate {--seed : Seed the Supabase database after migrating} {--redis : Sync fleet records and migration metadata into Redis after migrating} {--flush-redis : Remove the previous Redis fleet snapshot before syncing}', function () {
    $hasConnectionDetails = filled(env('SUPABASE_DB_URL'))
        || (filled(env('SUPABASE_DB_HOST')) && filled(env('SUPABASE_DB_PASSWORD')));

    if (! $hasConnectionDetails) {
        $this->error('Supabase database credentials are missing. Fill in the SUPABASE_DB_* values in your .env first.');

        return 1;
    }

    $exitCode = Artisan::call('migrate', [
        '--database' => 'supabase',
        '--force' => true,
    ], $this->output);

    if ($exitCode !== 0) {
        return $exitCode;
    }

    if ($this->option('seed')) {
        $seedExitCode = Artisan::call('db:seed', [
            '--database' => 'supabase',
            '--force' => true,
        ], $this->output);

        if ($seedExitCode !== 0) {
            return $seedExitCode;
        }
    }

    if ($this->option('redis')) {
        $syncExitCode = Artisan::call('redis:sync-fleet', array_filter([
            '--flush' => $this->option('flush-redis') ? true : null,
        ]), $this->output);

        if ($syncExitCode !== 0) {
            return $syncExitCode;
        }
    }

    $this->info('Supabase database migrations completed.');

    return 0;
})->purpose('Run Laravel migrations against the configured Supabase Postgres database');

Artisan::command('redis:sync-fleet {--flush : Remove the previous Redis fleet snapshot before syncing}', function () {
    /** @var RedisFleetSnapshotService $snapshotService */
    $snapshotService = app(RedisFleetSnapshotService::class);

    if (! $snapshotService->isConfigured()) {
        $this->error('The configured Redis fleet connection is missing. Add the REDIS_FLEET_* values to your .env first.');

        return 1;
    }

    $manifest = $snapshotService->sync((bool) $this->option('flush'));

    foreach ($manifest['datasets'] as $dataset => $details) {
        $this->line(sprintf('%s: %d record(s)', $dataset, $details['count']));
    }

    foreach ($manifest['migrations'] as $connection => $details) {
        $this->line(sprintf('migrations[%s]: %d entry(ies)', $connection, $details['count']));
    }

    $this->info(sprintf(
        'Redis fleet snapshot stored on the "%s" Redis connection with prefix "%s".',
        $manifest['redis_connection'],
        config('database.redis_sync.prefix')
    ));

    return 0;
})->purpose('Copy fleet records and migration metadata into the configured Redis database');
