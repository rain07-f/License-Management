<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Services\DomainService;
use App\Services\UpdateDeliveryService;
use Exception;

class UpdateController extends Controller
{
    protected $domainService;
    protected $updateService;

    public function __construct(DomainService $domainService, UpdateDeliveryService $updateService)
    {
        $this->domainService = $domainService;
        $this->updateService = $updateService;
    }

    public function updateCheck(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
            'application_slug' => 'required|string',
            'current_version' => 'required|string',
        ]);

        // Validate License and Domain first
        $validation = $this->domainService->validate(
            $request->license_key,
            $request->domain,
            $request->ip()
        );

        if (!$validation['success']) {
            return response()->json($validation, 400);
        }

        // Find Application
        $application = Application::where('slug', $request->application_slug)->first();
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found.'
            ], 404);
        }

        $checkResult = $this->updateService->checkUpdate($application, $request->current_version);

        if (!$checkResult['update_available']) {
            return response()->json([
                'success' => true,
                'update_available' => false,
                'message' => 'You are on the latest version.'
            ]);
        }

        // Generate download URL
        $downloadUrl = url('/api/v1/license/download?' . http_build_query([
            'license_key' => $request->license_key,
            'domain' => $request->domain,
            'application_slug' => $application->slug,
            'version' => $checkResult['latest_version'],
        ]));

        return response()->json([
            'success' => true,
            'update_available' => true,
            'new_version' => $checkResult['latest_version'],
            'download_url' => $downloadUrl,
            'requires_php' => $checkResult['requires_php'],
            'requires_wp' => $checkResult['requires_wp'],
            'release_notes' => $checkResult['release_notes'],
            'package_size' => $checkResult['package_size'],
            // WP Plugin Info Format Optional support
            'slug' => $application->slug,
            'version' => $checkResult['latest_version'],
            'package' => $downloadUrl,
        ]);
    }

    public function download(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
            'application_slug' => 'required|string',
            'version' => 'required|string',
        ]);

        // Validate License and Domain
        $validation = $this->domainService->validate(
            $request->license_key,
            $request->domain,
            $request->ip()
        );

        if (!$validation['success']) {
            return response()->json($validation, 403);
        }

        // Find Application
        $application = Application::where('slug', $request->application_slug)->first();
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found.'
            ], 404);
        }

        $filePath = $this->updateService->getDownloadFile($application, $request->version);

        if (!$filePath) {
            return response()->json([
                'success' => false,
                'message' => 'Update package file missing or corrupted.'
            ], 404);
        }

        $fileName = "{$application->slug}-{$request->version}.zip";
        return response()->download($filePath, $fileName);
    }
}
