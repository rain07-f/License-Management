<?php

namespace App\Http\Controllers;

use App\Models\ApplicationVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationVersionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'version' => 'required|string|max:255',
            'package' => 'required|file|mimes:zip|max:512000', // 500MB max
            'release_notes' => 'nullable|string',
            'min_php_version' => 'nullable|string|max:255',
            'min_wp_version' => 'nullable|string|max:255',
        ]);

        $file = $request->file('package');
        $fileName = $validated['version'] . '.zip';
        $path = $file->storeAs("applications/{$validated['application_id']}/{$validated['version']}", $fileName, 'public');

        try {
            ApplicationVersion::create([
                'application_id' => $validated['application_id'],
                'version' => $validated['version'],
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'release_notes' => $validated['release_notes'] ?? null,
                'min_php_version' => $validated['min_php_version'] ?? null,
                'min_wp_version' => $validated['min_wp_version'] ?? null,
            ]);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($path); // Cleanup on DB failure
            return back()->with('error', 'Failed to save version. ' . $e->getMessage());
        }

        return back()->with('success', "Version {$validated['version']} uploaded successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationVersion $applicationVersion)
    {
        // Delete the physical directory containing the version zip
        $directory = dirname($applicationVersion->file_path);
        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        $applicationVersion->delete();

        return back()->with('success', 'Version deleted successfully.');
    }
}
