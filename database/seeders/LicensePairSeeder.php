<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\User;
use App\Models\License;
use Illuminate\Support\Facades\Hash;

class LicensePairSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Plan
        $plan = Plan::firstOrCreate(
            ['name' => 'Enterprise SaaS Pair'],
            [
                'description' => '100 Activations with Domain+Device Pair Binding',
                'duration_days' => 365,
                'domain_limit' => 100, // Legacy field
                'activation_limit' => 100, // New field
                'price' => 999,
            ]
        );

        // 2. Create a Client
        $client = User::firstOrCreate(
            ['email' => 'pair-client@license.com'],
            [
                'name' => 'Pair Client Demo',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        // 3. Create a License
        $plainKey = 'PAIR-100-TEST-KEY';
        $hash = hash('sha256', $plainKey);

        $license = License::firstOrCreate(
            ['license_key_hash' => $hash],
            [
                'owner_id' => $client->id,
                'generated_by' => User::where('role', 'super_admin')->first()?->id ?? 1,
                'plan_id' => $plan->id,
                'license_key_display' => $plainKey,
                'status' => 'active',
                'expires_at' => now()->addYear(),
                'max_domains' => 100,
                'activation_quota' => 100,
            ]
        );

        $this->command->info('========================================');
        $this->command->info(' License Pair Seeder Complete');
        $this->command->info('========================================');
        $this->command->info(' License Key: ' . $plainKey);
        $this->command->info(' Quota      : ' . $license->activation_quota);
        $this->command->info(' Plan       : ' . $plan->name);
        $this->command->info('========================================');
    }
}
