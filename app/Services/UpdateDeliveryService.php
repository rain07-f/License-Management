<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Storage;

class UpdateDeliveryService
{
    /**
     * Check if a client version needs an update.
     */
    public function checkUpdate(Application $application, string $clientVersion)
    {
        $latestVersion = $application->latestVersion;

        if (!$latestVersion) {
            return [
                'update_available' => false,
                'message' => 'No versions available for this application.',
            ];
        }

        // Compare client version with latest. Returns 1 if latest is newer.
        $needsUpdate = version_compare($latestVersion->version, $clientVersion, '>');

        return [
            'update_available' => $needsUpdate,
            'latest_version' => $latestVersion->version,
            'current_version' => $clientVersion,
            'requires_php' => $latestVersion->min_php_version,
            'requires_wp' => $latestVersion->min_wp_version,
            'release_notes' => $latestVersion->release_notes,
            'package_size' => $latestVersion->file_size,
        ];
    }

    /**
     * Fetch the raw file for download.
     */
    public function getDownloadFile(Application $application, string $versionString)
    {
        $version = $application->versions()->where('version', $versionString)->first();
        
        if (!$version) {
            return null;
        }
        
        $path = $version->file_path;
        
        if (!Storage::disk('public')->exists($path)) {
            return null; // Physically missing from disk
        }
        
        return Storage::disk('public')->path($path);
    }
}
