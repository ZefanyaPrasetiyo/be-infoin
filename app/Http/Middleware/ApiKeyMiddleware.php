<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Bearer token required'
            ], 401);
        }

        if ($token !== env('API_KEY')) {
            return response()->json([
                'message' => 'Invalid token'
            ], 401);
        }

        return $next($request);
    }
}
