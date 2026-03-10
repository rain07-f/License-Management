<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $sessions = $this->getActiveSessions($user->id);

        return view('admin.profile.edit', [
            'user' => $user,
            'sessions' => $sessions,
            'current_session_id' => session()->getId()
        ]);
    }

    public function revokeSession($sessionId)
    {
        $cacheKey = 'login_session_' . $sessionId;
        $sessionData = Cache::get($cacheKey);

        if ($sessionData && $sessionData['user_id'] == auth()->id()) {
            Cache::forget($cacheKey);
            return response()->json(['message' => 'Session revoked successfully.']);
        }

        return response()->json(['message' => 'Unauthorized or session not found.'], 403);
    }

    public function revokeAllOtherSessions()
    {
        $user = auth()->user();
        $currentSessionId = session()->getId();
        $prefix = config('cache.prefix');
        
        $rawSessions = DB::table('cache')
            ->where('key', 'like', $prefix . 'login_session_%')
            ->get();

        foreach ($rawSessions as $raw) {
            $key = str_replace($prefix, '', $raw->key);
            $sessionId = str_replace('login_session_', '', $key);
            
            if ($sessionId === $currentSessionId) continue;

            $data = Cache::get($key);
            if ($data && isset($data['user_id']) && $data['user_id'] == $user->id) {
                Cache::forget($key);
            }
        }

        return response()->json(['message' => 'All other sessions have been revoked.']);
    }

    private function getActiveSessions($userId)
    {
        $prefix = config('cache.prefix');
        $rawSessions = DB::table('cache')
            ->where('key', 'like', $prefix . 'login_session_%')
            ->get();

        $activeSessions = [];
        foreach ($rawSessions as $raw) {
            // Remove prefix to get the actual key
            $key = str_replace($prefix, '', $raw->key);
            $sessionId = str_replace('login_session_', '', $key);

            $data = Cache::get($key);

            if ($data && isset($data['user_id']) && $data['user_id'] == $userId) {
                $activeSessions[] = array_merge($data, [
                    'session_id' => $sessionId,
                    'is_current' => $sessionId === session()->getId(),
                    'device' => $this->parseUserAgent($data['user_agent'] ?? '')
                ]);
            }
        }

        // Sort: current session first, then by login time
        usort($activeSessions, function ($a, $b) {
            if ($a['is_current']) return -1;
            if ($b['is_current']) return 1;
            return $b['login_at'] <=> $a['login_at'];
        });

        return $activeSessions;
    }

    private function parseUserAgent($userAgent)
    {
        $browser = "Unknown Browser";
        $platform = "Unknown OS";

        // Simple Browser Detection
        if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) $browser = 'Internet Explorer';
        elseif (preg_match('/Firefox/i', $userAgent)) $browser = 'Firefox';
        elseif (preg_match('/Chrome/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/Safari/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/Opera/i', $userAgent)) $browser = 'Opera';
        elseif (preg_match('/Netscape/i', $userAgent)) $browser = 'Netscape';

        // Simple Platform Detection
        if (preg_match('/windows|win32/i', $userAgent)) $platform = 'Windows';
        elseif (preg_match('/macintosh|mac os x/i', $userAgent)) $platform = 'Mac OS';
        elseif (preg_match('/linux/i', $userAgent)) $platform = 'Linux';
        elseif (preg_match('/iphone/i', $userAgent)) $platform = 'iPhone';
        elseif (preg_match('/android/i', $userAgent)) $platform = 'Android';

        return "$browser on $platform";
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'The provided password does not match our records.'
                ], 422);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->fill($validated);
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();

            return response()->json([
                'message' => 'Avatar updated successfully!',
                'avatar_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['message' => 'No file uploaded.'], 422);
    }
}
