<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = $request->session()->getId();
        $cacheKey = 'login_session_' . $sessionId;

        if (!Cache::has($cacheKey)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired or invalid.',
                    'redirect' => route('login')
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
        }

        // Extend cache TTL to keep session alive during activity
        Cache::put($cacheKey, Cache::get($cacheKey), now()->addHours(12));

        return $next($request);
    }
}
