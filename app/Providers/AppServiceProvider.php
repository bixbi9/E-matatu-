<?php

namespace App\Providers;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // On Windows the system CA bundle is often missing. Point cURL at our
        // downloaded cacert.pem so HTTPS requests (e.g. to Supabase) work.
        $cacert = base_path('.tools/php-8.2.30/cacert.pem');
        if (PHP_OS_FAMILY === 'Windows' && file_exists($cacert)) {
            Http::globalOptions(['verify' => $cacert]);
        }
    }
}
