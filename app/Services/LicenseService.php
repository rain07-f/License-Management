<?php

namespace App\Services;

use App\Models\License;
use App\Models\LicenseLog;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class LicenseService
{
    /**
     * Generate a new license for a user.
     */
    public function generate(User $generator, User $owner, Plan $plan): License
    {
        return DB::transaction(function () use ($generator, $owner, $plan) {
            // Check quota if distributor
            if ($generator->isDistributor()) {
                if ($generator->license_quota <= 0) {
                    throw new Exception("You have no remaining license quota.");
                }
                $generator->decrement('license_quota');
            }

            $plainKey = $this->generateSecureKey();
            $hash = hash('sha256', $plainKey);

            $license = License::create([
                'owner_id' => $owner->id,
                'generated_by' => $generator->id,
                'plan_id' => $plan->id,
                'license_key_hash' => $hash,
                'license_key_display' => $plainKey, // Shown only once
                'status' => 'active',
                'expires_at' => now()->addDays($plan->duration_days),
                'max_domains' => $plan->domain_limit,
            ]);

            $this->logAction($license, $generator, 'generate');

            return $license;
        });
    }

    /**
     * Generate format LIC-XXXX-XXXX-XXXX-XXXX
     */
    private function generateSecureKey(): string
    {
        $parts = [];
        for ($i = 0; $i < 4; $i++) {
            $parts[] = strtoupper(Str::random(4));
        }
        return 'LIC07-' . implode('-', $parts);
    }

    /**
     * Validate a license key hash.
     */
    public function validateHash(string $plainKey, ?string $domain = null): ?License
    {
        $hash = hash('sha256', $plainKey);
        $license = License::where('license_key_hash', $hash)
            ->with(['plan', 'domains'])
            ->first();

        if (!$license) {
            return null;
        }

        return $license;
    }

    /**
     * Renew a license using a plan.
     */
    public function renew(License $license, User $user, Plan $plan): License
    {
        return DB::transaction(function () use ($license, $user, $plan) {
            // Deduct quota if distributor
            if ($user->isDistributor()) {
                if ($user->license_quota <= 0) {
                    throw new Exception("You have no remaining license quota.");
                }
                $user->decrement('license_quota');
            }

            // Calculate new expiration
            $baseDate = $license->expires_at && $license->expires_at->isFuture()
                ? $license->expires_at
                : now();

            $newExpiration = $baseDate->addDays($plan->duration_days);

            $license->update([
                'plan_id' => $plan->id,
                'expires_at' => $newExpiration,
                'status' => 'active', // Reactivate if expired/revoked
            ]);

            $this->logAction($license, $user, 'renew');

            return $license;
        });
    }

    /**
     * Log license action.
     */
    public function logAction(License $license, ?User $user, string $action, ?string $domain = null, ?string $ip = null): void
    {
        LicenseLog::create([
            'license_id' => $license->id,
            'user_id' => $user?->id,
            'domain' => $domain,
            'ip_address' => $ip,
            'action' => $action,
        ]);
    }
}
