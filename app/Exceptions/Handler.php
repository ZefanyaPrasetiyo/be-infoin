<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        // Selalu return JSON untuk semua request
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        ], 500);
    }
}