<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\License;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class LicensePairBindingTest extends TestCase
{
    use RefreshDatabase;

    protected $apiKey;
    protected $license;
    protected $plainKey = 'PAIR-TEST-123';

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create API Key
        $plainApiKey = 'test-api-key';
        ApiKey::create([
            'name' => 'Test Key',
            'key_hash' => hash('sha256', $plainApiKey),
            'key_enc' => Crypt::encryptString($plainApiKey),
            'is_active' => true
        ]);
        $this->apiKey = $plainApiKey;

        // 2. Create Super Admin
        $admin = User::factory()->create(['role' => 'super_admin']);

        // 3. Create Plan
        $plan = Plan::create([
            'name' => 'Test Plan',
            'duration_days' => 30,
            'domain_limit' => 2,
            'activation_limit' => 2,
            'price' => 10,
        ]);

        // 4. Create License
        $this->license = License::create([
            'owner_id' => $admin->id,
            'generated_by' => $admin->id,
            'plan_id' => $plan->id,
            'license_key_hash' => hash('sha256', $this->plainKey),
            'license_key_display' => $this->plainKey,
            'status' => 'active',
            'max_domains' => 2,
            'activation_quota' => 2,
            'expires_at' => now()->addDays(30),
        ]);
    }

    public function test_initial_activation_success()
    {
        $response = $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device1',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('license_activations', [
            'license_id' => $this->license->id,
            'domain' => 'domain1.com',
            'device_uid' => 'device1',
            'status' => 'active'
        ]);
    }

    public function test_activation_is_idempotent()
    {
        // First time
        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device1',
        ], ['X-API-Key' => $this->apiKey]);

        // Second time (Same pair)
        $response = $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device1',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200);
        $this->assertEquals(1, \App\Models\LicenseActivation::count());
    }

    public function test_domain_is_locked_even_with_diff_device()
    {
        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device1',
        ], ['X-API-Key' => $this->apiKey]);

        $response = $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device2',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Domain already locked.']);
    }

    public function test_device_is_locked_even_with_diff_domain()
    {
        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device1',
        ], ['X-API-Key' => $this->apiKey]);

        $response = $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain2.com',
            'device_uid' => 'device1',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Device locked.']);
    }

    public function test_revocation_works_but_locks_the_pair_forever()
    {
        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device1',
        ], ['X-API-Key' => $this->apiKey]);

        // Revoke
        $this->postJson('/api/v1/license-pair/revoke', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
        ], ['X-API-Key' => $this->apiKey])->assertStatus(200);

        // Try to activate AGAIN with SAME domain or device (REJECT)
        $response = $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'domain1.com',
            'device_uid' => 'device2',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(403);
    }

    public function test_quota_is_enforced()
    {
        // 1. First pair (Success)
        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'd1.com',
            'device_uid' => 'dev1',
        ], ['X-API-Key' => $this->apiKey])->assertStatus(200);

        // 2. Second pair (Success)
        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'd2.com',
            'device_uid' => 'dev2',
        ], ['X-API-Key' => $this->apiKey])->assertStatus(200);

        // 3. Third pair (FAIL - Quota = 2)
        $response = $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'd3.com',
            'device_uid' => 'dev3',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Activation quota exceeded.']);
    }

    public function test_revocation_does_not_free_slot()
    {
        // Consuming all quota (2)
        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'd1.com',
            'device_uid' => 'dev1',
        ], ['X-API-Key' => $this->apiKey])->assertStatus(200);

        $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'd2.com',
            'device_uid' => 'dev2',
        ], ['X-API-Key' => $this->apiKey])->assertStatus(200);

        // Revoke one
        $this->postJson('/api/v1/license-pair/revoke', [
            'license_key' => $this->plainKey,
            'domain' => 'd1.com',
        ], ['X-API-Key' => $this->apiKey])->assertStatus(200);

        // Try to add a NEW pair (FAIL - Slot remains consumed)
        $response = $this->postJson('/api/v1/license-pair/activate', [
            'license_key' => $this->plainKey,
            'domain' => 'd3.com',
            'device_uid' => 'dev3',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(403);
    }
}
