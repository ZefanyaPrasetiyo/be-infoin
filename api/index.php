<?php

// Matikan semua error view
error_reporting(0);
ini_set('display_errors', 0);

// Override view resolver SEBELUM Laravel jalan
$app = require __DIR__ . '/../bootstrap/app.php';

// Bind view ke null biar gak error
$app->bind('view', function() {
    return new class {
        public function make($view, $data = []) {
            return response()->json(['error' => 'View not found'], 404);
        }
    };
});

// Jalanin request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);