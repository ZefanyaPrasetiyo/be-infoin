<?php

// api/index.php - ultra minimal

use Illuminate\Http\Request;

// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override view dengan null
$app->singleton('view', function() {
    return null;
});

// Handle request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
);
$response->send();
$kernel->terminate($request, $response);