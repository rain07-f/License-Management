<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /**
     * Maximum allowed active sessions per user.
     */
    protected $maxSessions = 5;

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $sessionId = $request->session()->getId();

            $this->enforceSessionLimit(Auth::id());

            \Illuminate\Support\Facades\Cache::put(
                'login_session_' . $sessionId,
                [
                    'user_id' => Auth::id(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_at' => now()
                ],
                now()->addHours(12)
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful, redirecting...',
                    'redirect' => route('admin.dashboard')
                ]);
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.'
            ], 422);
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $sessionId = $request->session()->getId();
        \Illuminate\Support\Facades\Cache::forget('login_session_' . $sessionId);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
                'redirect' => url('/')
            ]);
        }

        return redirect('/');
    }

    private function enforceSessionLimit($userId)
    {
        $prefix = config('cache.prefix');
        $rawSessions = DB::table('cache')
            ->where('key', 'like', $prefix . 'login_session_%')
            ->get();

        $activeSessions = [];
        foreach ($rawSessions as $raw) {
            $key = str_replace($prefix, '', $raw->key);
            $data = Cache::get($key);

            if ($data && isset($data['user_id']) && $data['user_id'] == $userId) {
                $activeSessions[] = [
                    'key' => $key,
                    'time' => $data['login_at']
                ];
            }
        }

        if (count($activeSessions) >= $this->maxSessions) {
            // Sort by time ascending (oldest first)
            usort($activeSessions, function ($a, $b) {
                return $a['time'] <=> $b['time'];
            });

            // Remove oldest sessions until we are under the limit
            $toRemove = count($activeSessions) - $this->maxSessions + 1;
            for ($i = 0; $i < $toRemove; $i++) {
                Cache::forget($activeSessions[$i]['key']);
            }
        }
    }
}
