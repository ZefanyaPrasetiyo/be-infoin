<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php', // Langsung pake api, tanpa then
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.key' => ApiKeyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Return JSON buat semua error
        $exceptions->render(function (Throwable $e, $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'type' => class_basename($e),
            ], 500);
        });
    })
    ->create();