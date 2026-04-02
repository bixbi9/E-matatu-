<?php

$root = dirname(__DIR__);
$publicDir = $root . '/public';

// Fix document root
$_SERVER['DOCUMENT_ROOT'] = $publicDir;

// On Vercel the filesystem is read-only except /tmp — redirect writable paths
if (!is_writable($root . '/storage')) {
    $tmpStorage = '/tmp/storage';
    foreach (['app/public', 'framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $dir) {
        @mkdir($tmpStorage . '/' . $dir, 0775, true);
    }
    // Override storage path via env so Laravel picks it up
    putenv("STORAGE_PATH={$tmpStorage}");
    $_ENV['STORAGE_PATH'] = $tmpStorage;
    $_SERVER['STORAGE_PATH'] = $tmpStorage;
}

// Serve static files that slipped past Vercel routes
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$staticFile = $publicDir . $requestUri;

if ($requestUri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    return false;
}

chdir($publicDir);
require "{$publicDir}/index.php";
