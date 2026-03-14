<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\DomainService;
use Exception;

class LicenseController extends Controller
{
    protected $domainService;

    public function __construct(DomainService $domainService)
    {
        $this->domainService = $domainService;
    }

    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
        ]);

        try {
            $this->domainService->activate(
                $request->license_key,
                $request->domain,
                null,
                $request->ip()
            );

            return response()->json([
                'success' => true,
                'message' => 'License activated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function validate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
        ]);

        $result = $this->domainService->validate(
            $request->license_key,
            $request->domain,
            $request->ip()
        );

        if ($result['success']) {
            return response()->json([
                'valid' => true,
                'plan' => $result['data']['plan'] ?? null,
                'max_domains' => $result['data']['max_domains'] ?? 0,
                'domains_used' => $result['data']['domains_used'] ?? 0,
                'expires_at' => $result['data']['expires_at'] ?? null,
            ], 200);
        }

        return response()->json([
            'valid' => false,
            'message' => $result['message'],
        ], 400);
    }

    public function deactivate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'domain' => 'required|string',
        ]);

        try {
            $this->domainService->deactivate(
                $request->license_key,
                $request->domain,
                null,
                $request->ip()
            );

            return response()->json([
                'success' => true,
                'message' => 'Domain deactivated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
