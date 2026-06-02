<?php
// api/index.php - versi debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require __DIR__ . '/../public/index.php';
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}