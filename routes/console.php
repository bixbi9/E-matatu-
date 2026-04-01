<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('supabase:migrate {--seed : Seed the Supabase database after migrating}', function () {
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
        return Artisan::call('db:seed', [
            '--database' => 'supabase',
            '--force' => true,
        ], $this->output);
    }

    $this->info('Supabase database migrations completed.');

    return 0;
})->purpose('Run Laravel migrations against the configured Supabase Postgres database');
