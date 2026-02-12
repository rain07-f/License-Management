<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $serverKey = env('LICENSE_API_KEY');

        // If security key is not defined, we skip the check
        if (!$serverKey) {
            return $next($request);
        }

        $requestKey = $request->header('X-API-Key');

        if ($requestKey !== $serverKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or missing X-API-Key header.',
            ], 401);
        }

        return $next($request);
    }
}
