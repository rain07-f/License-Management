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
        Log::info('Update request initiated. Form Type: ' . $request->input('form_type'), $request->all());
        $user = auth()->user();

        // Case 1: Password Update
        if ($request->input('form_type') === 'password') {
            Log::info('Entering password update logic for user: ' . $user->email);
            try {
                $request->validate([
                    'current_password' => ['required', 'current_password'],
                    'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Validation failed for user ' . $user->id . ':', $e->errors());
                throw $e;
            }

            // Rely on the "hashed" cast in User model. 
            // Setting it as a string will trigger the cast-hashing.
            $user->password = $request->password;
            
            if ($user->save()) {
                Log::info('Password saved successfully to database for user: ' . $user->id);
                
                // Secure other devices and update current session with new hash
                Auth::logoutOtherDevices($request->password);
                
                // Re-authenticate to ensure session is crystal clear
                Auth::login($user);
                $request->session()->regenerate();
            } else {
                Log::error('FAILED to save password to database for user: ' . $user->id);
            }

            return response()->json([
                'message' => 'Password updated successfully! Other sessions secured.',
            ]);
        }
        
        Log::info('Falling back to profile update branch for user: ' . $user->email);

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
