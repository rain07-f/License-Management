<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
            'key' => ApiKey::hash($plainKey),
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
}
