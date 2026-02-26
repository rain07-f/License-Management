<?php

namespace App\Services;

use App\Models\License;
use App\Models\LicenseActivation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;

class LicenseActivationService
{
    /**
     * Activate a license for a specific domain and device pair.
     */
    public function activate(string $licenseKey, string $domain, string $deviceUid, ?string $fingerprint = null, ?int $timestamp = null)
    {
        return DB::transaction(function () use ($licenseKey, $domain, $deviceUid, $fingerprint, $timestamp) {
            // 1. Find and validate license
            $license = $this->findAndValidateLicense($licenseKey);

            // Optional: Advanced Security - Fingerprint & Timestamp
            if ($fingerprint || $timestamp) {
                $this->verifySecurity($domain, $deviceUid, $fingerprint, $timestamp);
            }

            // 2. Check Existing Activation
            $existing = LicenseActivation::where('license_id', $license->id)
                ->where(function ($query) use ($domain, $deviceUid) {
                    $query->where('domain', $domain)
                        ->orWhere('device_uid', $deviceUid);
                })
                ->first();

            if ($existing) {
                // CASE A: Exact same pair exists & active -> IDEMPOTENT Success
                if ($existing->domain === $domain && $existing->device_uid === $deviceUid) {
                    if ($existing->status === 'active') {
                        return $license;
                    }

                    // CASE B: Same pair exists but revoked -> Reactivate (Freeing the slot was the goal)
                    $existing->update([
                        'status' => 'active',
                        'activated_at' => now(),
                        'revoked_at' => null
                    ]);
                    return $license;
                }

                // CASE C: Another active activation uses this domain or device
                if ($existing->status === 'active') {
                    if ($existing->domain === $domain) {
                        throw new Exception("Domain already in use by another active activation.", 403);
                    }
                    if ($existing->device_uid === $deviceUid) {
                        throw new Exception("Device already in use by another active activation.", 403);
                    }
                }

                // If it was revoked, we fall through to create a new record (or reuse the logic above)
            }

            // 3. Check Quota (Count only ACTIVE activations)
            $activeCount = LicenseActivation::where('license_id', $license->id)
                ->where('status', 'active')
                ->count();

            if ($activeCount >= $license->activation_quota) {
                throw new Exception("Activation quota exceeded. Please revoke an old activation first.", 403);
            }

            // 4. Insert Activation
            LicenseActivation::create([
                'license_id' => $license->id,
                'domain' => $domain,
                'device_uid' => $deviceUid,
                'status' => 'active',
                'activated_at' => now(),
            ]);

            return $license;
        });
    }

    /**
     * Revoke an activation for a domain.
     */
    public function revoke(string $licenseKey, string $domain)
    {
        return DB::transaction(function () use ($licenseKey, $domain) {
            $license = $this->findAndValidateLicense($licenseKey);

            $activation = LicenseActivation::where('license_id', $license->id)
                ->where('domain', $domain)
                ->where('status', 'active')
                ->first();

            if (!$activation) {
                throw new Exception("Active activation not found for this domain.", 404);
            }

            $activation->update([
                'status' => 'revoked',
                'revoked_at' => now(),
            ]);

            return true;
        });
    }

    /**
     * Validate if a pair is active.
     */
    public function validate(string $licenseKey, string $domain, string $deviceUid)
    {
        $license = $this->findAndValidateLicense($licenseKey);

        $exists = LicenseActivation::where('license_id', $license->id)
            ->where('domain', $domain)
            ->where('device_uid', $deviceUid)
            ->where('status', 'active')
            ->exists();

        if (!$exists) {
            throw new Exception("License activation invalid or expired.", 403);
        }

        return $license;
    }

    /**
     * Find license by key hash and initial check.
     */
    private function findAndValidateLicense(string $licenseKey): License
    {
        $hash = hash('sha256', $licenseKey);
        $license = License::where('license_key_hash', $hash)->first();

        if (!$license) {
            throw new Exception("Invalid license key.", 404);
        }

        if ($license->status !== 'active') {
            throw new Exception("License is " . $license->status . ".", 403);
        }

        if ($license->expires_at && $license->expires_at->isPast()) {
            throw new Exception("License has expired.", 403);
        }

        return $license;
    }

    /**
     * Optional security verification.
     */
    private function verifySecurity(string $domain, string $deviceUid, ?string $fingerprint, ?int $timestamp)
    {
        // 1. Timestamp validation (Anti-replay: 5 minute window)
        if ($timestamp) {
            if (abs(time() - $timestamp) > 300) {
                throw new Exception("Request expired (anti-replay check failed).", 422);
            }
        }

        // 2. Fingerprint verification
        if ($fingerprint) {
            $appKey = config('app.key');
            $expected = hash('sha256', $domain . $deviceUid . $appKey);

            if (!hash_equals($expected, $fingerprint)) {
                throw new Exception("Invalid request fingerprint.", 422);
            }
        }
    }
}
