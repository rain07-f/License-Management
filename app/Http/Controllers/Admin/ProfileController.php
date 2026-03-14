<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Case 1: Password Update
        if ($request->input('form_type') === 'password') {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);

            // Rely on the "hashed" cast in User model
            $user->password = $request->password;

            if ($user->save()) {
                // Secure other devices and update current session with new hash
                Auth::logoutOtherDevices($request->password);

                Auth::login($user);
                $request->session()->regenerate();

                if ($request->ajax()) {
                    return response()->json([
                        'message' => 'Password updated successfully! Other sessions secured.',
                        'redirect' => route('admin.profile.edit') . '#security-settings'
                    ]);
                }

                return redirect(route('admin.profile.edit') . '#security-settings')
                    ->with('success', 'Password updated successfully!');
            }

            if ($request->ajax()) {
                return response()->json(['message' => 'Failed to save password.'], 500);
            }

            return back()->with('error', 'Failed to save password.');
        }

        // Case 2: Profile Update
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->fill($validated);
        if ($user->save()) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Profile updated successfully!',
                ]);
            }

            return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully!');
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Failed to update profile.'], 500);
        }

        return back()->with('error', 'Failed to update profile.');
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
