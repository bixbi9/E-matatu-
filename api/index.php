<?php

$root = dirname(__DIR__);
$publicDir = $root . '/public';

// On Vercel the root filesystem is read-only; only /tmp is writable.
// Create all directories Laravel needs before the framework boots.
$tmpBase = '/tmp/laravel';

$writableDirs = [
    "{$tmpBase}/storage/app/public",
    "{$tmpBase}/storage/framework/cache/data",
    "{$tmpBase}/storage/framework/sessions",
    "{$tmpBase}/storage/framework/views",
    "{$tmpBase}/storage/logs",
    "{$tmpBase}/bootstrap/cache",
];

foreach ($writableDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

// Tell bootstrap/app.php where to find writable storage.
// Note: useBootstrapPath() takes the parent bootstrap/ dir; Laravel appends /cache itself.
putenv("VERCEL_STORAGE_PATH={$tmpBase}/storage");
putenv("VERCEL_BOOTSTRAP_PATH={$tmpBase}/bootstrap");
$_ENV['VERCEL_STORAGE_PATH']   = "{$tmpBase}/storage";
$_ENV['VERCEL_BOOTSTRAP_PATH'] = "{$tmpBase}/bootstrap";

// Fix document root so asset URL helpers resolve correctly.
$_SERVER['DOCUMENT_ROOT'] = $publicDir;

// Pass through static files that slipped past Vercel routes.
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$staticFile = $publicDir . $requestUri;

if ($requestUri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    return false;
}

chdir($publicDir);
require "{$publicDir}/index.php";
