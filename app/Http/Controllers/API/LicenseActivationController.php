<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ActivateLicensePairRequest;
use App\Http\Requests\API\RevokeLicensePairRequest;
use App\Services\LicenseActivationService;
use App\Services\LicenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class LicenseActivationController extends Controller
{
    protected $activationService;
    protected $licenseService;

    public function __construct(LicenseActivationService $activationService, LicenseService $licenseService)
    {
        $this->activationService = $activationService;
        $this->licenseService = $licenseService;
    }

    /**
     * Activate license pair.
     */
    public function activate(ActivateLicensePairRequest $request): JsonResponse
    {
        try {
            $domain = $this->detectDomain($request);
            $deviceUid = $this->detectDeviceUid($request);

            $license = $this->activationService->activate(
                $request->license_key,
                $domain,
                $deviceUid,
                $request->fingerprint,
                $request->timestamp
            );

            // Log action
            $this->licenseService->logAction($license, null, 'activate', $domain, $request->ip());

            return response()->json([
                'status' => 'success',
                'message' => 'License activated',
                'expires_at' => $license->expires_at?->toDateString(),
                'detected_domain' => $domain,
                'detected_device' => $deviceUid,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * Revoke activation.
     */
    public function revoke(RevokeLicensePairRequest $request): JsonResponse
    {
        try {
            $domain = $this->detectDomain($request);
            $this->activationService->revoke($request->license_key, $domain);

            return response()->json([
                'status' => 'success',
                'message' => 'License activation revoked',
                'detected_domain' => $domain,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * Validate activation.
     */
    public function validatePair(ActivateLicensePairRequest $request): JsonResponse
    {
        try {
            $domain = $this->detectDomain($request);
            $deviceUid = $this->detectDeviceUid($request);

            $license = $this->activationService->validate(
                $request->license_key,
                $domain,
                $deviceUid
            );

            return response()->json([
                'status' => 'success',
                'message' => 'License valid',
                'expires_at' => $license->expires_at?->toDateString(),
                'detected_domain' => $domain,
                'detected_device' => $deviceUid,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * Detect domain from parameters or request headers.
     */
    private function detectDomain(Request $request): string
    {
        // 1. Explicitly provided
        if ($request->filled('domain')) {
            return $request->domain;
        }

        // 2. Referer Header
        if ($referer = $request->headers->get('referer')) {
            $host = parse_url($referer, PHP_URL_HOST);
            if ($host)
                return $host;
        }

        // 3. Origin Header
        if ($origin = $request->headers->get('origin')) {
            $host = parse_url($origin, PHP_URL_HOST);
            if ($host)
                return $host;
        }

        // 4. Default Host
        return $request->getHost();
    }

    /**
     * Detect or generate a Device UID.
     */
    private function detectDeviceUid(Request $request): string
    {
        // 1. Explicitly provided
        if ($request->filled('device_uid')) {
            return $request->device_uid;
        }

        // 2. Fallback: Deterministic fingerprint (IP + UserAgent)
        $ip = $request->ip();
        $ua = $request->userAgent() ?: 'unknown';

        return hash('sha256', "DEVICE-{$ip}-{$ua}");
    }
}
