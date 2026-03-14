<?php

namespace App\Services;

use App\Models\License;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class DomainService
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Activate a domain for a license key.
     */
    public function activate(string $licenseKey, string $domainName, ?User $user = null, ?string $ip = null)
    {
        $license = $this->licenseService->validateHash($licenseKey);

        if (!$license) {
            throw new Exception("Invalid license key.");
        }

        if ($license->status !== 'active') {
            throw new Exception("License is not active (Status: {$license->status}).");
        }

        if ($license->expires_at && $license->expires_at->isPast()) {
            throw new Exception("License has expired.");
        }

        return DB::transaction(function () use ($license, $domainName, $user, $ip) {
            $domain = Domain::where('license_id', $license->id)
                ->where('domain_name', $domainName)
                ->first();

            if ($domain) {
                // Already activated, update check time
                $domain->update(['last_check_at' => now()]);
            } else {
                // New activation, check limit
                if ($license->domains()->count() >= $license->max_domains) {
                    throw new Exception("Domain activation limit reached ({$license->max_domains}).");
                }

                $domain = Domain::create([
                    'license_id' => $license->id,
                    'domain_name' => $domainName,
                    'activated_by' => $user?->id ?? $license->owner_id,
                    'activated_at' => now(),
                    'last_check_at' => now(),
                    'status' => 'active',
                ]);
            }

            $this->licenseService->logAction($license, $user, 'activate', $domainName, $ip);

            return $license;
        });
    }

    /**
     * Validate if a domain is active for a license key.
     */
    public function validate(string $licenseKey, string $domainName, ?string $ip = null)
    {
        $license = $this->licenseService->validateHash($licenseKey);

        if (!$license) {
            return ['success' => false, 'message' => 'Invalid license key.'];
        }

        if ($license->status !== 'active') {
            return ['success' => false, 'message' => "License is {$license->status}."];
        }

        if ($license->expires_at && $license->expires_at->isPast()) {
            return ['success' => false, 'message' => 'License has expired.'];
        }

        $domain = Domain::where('license_id', $license->id)
            ->where('domain_name', $domainName)
            ->where('status', 'active')
            ->first();

        if (!$domain) {
            return ['success' => false, 'message' => 'Domain not registered for this license.'];
        }

        $domain->update(['last_check_at' => now()]);
        $this->licenseService->logAction($license, null, 'validate', $domainName, $ip);

        return [
            'success' => true,
            'message' => 'License is valid.',
            'data' => [
                'expires_at' => $license->expires_at?->toIso8601String(),
                'plan' => $license->plan->name,
                'max_domains' => $license->max_domains,
                'domains_used' => $license->domains()->count(),
            ]
        ];
    }

    /**
     * Deactivate a domain.
     */
    public function deactivate(string $licenseKey, string $domainName, ?User $user = null, ?string $ip = null)
    {
        $license = $this->licenseService->validateHash($licenseKey);

        if (!$license) {
            throw new Exception("Invalid license key.");
        }

        $domain = Domain::where('license_id', $license->id)
            ->where('domain_name', $domainName)
            ->first();

        if ($domain) {
            $domain->delete();
            $this->licenseService->logAction($license, $user, 'deactivate', $domainName, $ip);
        }

        return true;
    }
}
