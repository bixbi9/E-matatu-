<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust Vercel's edge proxy so Laravel sees HTTPS and generates correct URLs.
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_PREFIX,
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// On Vercel the filesystem is read-only outside of /tmp.
// api/index.php sets these env vars; redirect writable paths to /tmp.
if (($storagePath = env('VERCEL_STORAGE_PATH')) && is_dir($storagePath)) {
    $app->useStoragePath($storagePath);
}

if (($bootstrapPath = env('VERCEL_BOOTSTRAP_PATH')) && is_dir($bootstrapPath)) {
    $app->useBootstrapPath($bootstrapPath);
}

return $app;
