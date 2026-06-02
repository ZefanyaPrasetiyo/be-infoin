<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // HAPUS web routing karena gak dipake di Vercel (API-only)
        // web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',  // Set API sebagai primary
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Hapus middleware web yang gak perlu (HandleAppearance, HandleInertiaRequests, dll)
        // Karena itu buat frontend Laravel (Inertia/React/Vue)
        
        $middleware->api(prepend: [
            // Tambahin middleware global untuk API
        ]);
        
        $middleware->alias([
            'api.key' => ApiKeyMiddleware::class,
        ]);
        
        // Matiin session & cookie buat API (biar gak error)
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Selalu return JSON untuk semua error
        $exceptions->shouldRenderJsonWhen(function () {
            return true;
        });
        
        // Optional: handle 404 jadi JSON
        $exceptions->render(function (Throwable $e, $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], 500);
        });
    })
    ->create();