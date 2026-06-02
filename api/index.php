<?php
// api/index.php

// Set proper paths
$root = __DIR__ . '/..';
$publicPath = $root . '/public';
$bootstrapPath = $root . '/bootstrap/app.php';

// Load autoloader
require $root . '/vendor/autoload.php';

// Create application
$app = require_once $bootstrapPath;

// Set Laravel to use public/index.php as base
$app->usePublicPath($publicPath);

// Handle request
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate($request, $response);