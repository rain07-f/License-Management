<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ApiKeyController extends Controller
{
    /**
     * Display a listing of the API keys.
     */
    public function index(): View
    {
        $apiKeys = ApiKey::latest()->paginate(10);
        return view('admin.api_keys.index', compact('apiKeys'));
    }

    /**
     * Store a newly created API key in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $plainKey = ApiKey::generate();

        ApiKey::create([
            'name' => $request->name,
            'key_hash' => ApiKey::hash($plainKey),
            'key_enc' => Crypt::encryptString($plainKey),
            'is_active' => true,
        ]);


        return Redirect::route('admin.api-keys.index')
            ->with('success', 'API Key generated successfully.')
            ->with('api_key', $plainKey);
    }

    /**
     * Remove (deactivate) the specified API key from storage.
     */
    public function destroy(ApiKey $apiKey)
    {
        $apiKey->update(['is_active' => false]);
        return Redirect::route('admin.api-keys.index')
            ->with('success', 'API Key has been deactivated.');
    }

    /**
     * Reactivate the specified API key.
     */
    public function activate(ApiKey $apiKey)
    {
        $apiKey->update(['is_active' => true]);
        return Redirect::route('admin.api-keys.index')
            ->with('success', 'API Key has been reactivated.');
    }

    /**
     * Permanently delete the specified API key.
     */
    public function permanentDelete(ApiKey $apiKey)
    {
        $apiKey->delete();
        return Redirect::route('admin.api-keys.index')
            ->with('success', 'API Key has been permanently deleted.');
    }

    /**
     * Securely reveal the full API key.
     * 
     * @param ApiKey $apiKey
     * @return JsonResponse
     */
    public function reveal(ApiKey $apiKey): JsonResponse
    {
        // Require super admin role for revealing keys
        if (!Auth::user()->isSuperAdmin()) {
            Log::warning('Unauthorized API key reveal attempt', [
                'user_id' => Auth::id(),
                'api_key_id' => $apiKey->id,
                'ip' => request()->ip(),
            ]);
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $decryptedKey = Crypt::decryptString($apiKey->key_enc);

            Log::info('API key revealed', [
                'user_id' => Auth::id(),
                'api_key_id' => $apiKey->id,
                'ip' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'key' => $decryptedKey,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt API key', [
                'api_key_id' => $apiKey->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reveal key. It might be stored in an old format or encryption keys have changed.',
            ], 500);
        }
    }
}
