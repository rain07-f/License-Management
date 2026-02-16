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
        $requestKey = $request->header('X-API-Key');

        if (!$requestKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key missing',
            ], 401);
        }

        // Hash the incoming key to compare with the stored hash
        $hashedKey = \App\Models\ApiKey::hash($requestKey);

        $apiKey = \App\Models\ApiKey::active()
            ->where('key_hash', $hashedKey)
            ->first();

        if (!$apiKey) {
            \Illuminate\Support\Facades\Log::warning('Unauthorized API access attempt', [
                'ip' => $request->ip(),
                'header' => $requestKey ? 'provided' : 'missing',
                'url' => $request->fullUrl(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid API key',
            ], 403);
        }

        return $next($request);
    }
}
