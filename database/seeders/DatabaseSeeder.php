<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@license.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // Create Sample Plans
        \App\Models\Plan::create([
            'name' => 'Basic Plugin',
            'description' => 'Single domain license for 1 year',
            'duration_days' => 365,
            'domain_limit' => 1,
            'activation_limit' => 10,
            'price' => 29.00,
        ]);

        \App\Models\Plan::create([
            'name' => 'Pro Plugin',
            'description' => '3 domains license for 1 year',
            'duration_days' => 365,
            'domain_limit' => 3,
            'activation_limit' => 50,
            'price' => 79.00,
        ]);

        \App\Models\Plan::create([
            'name' => 'Enterprise SaaS',
            'description' => 'Unlimited domains license for 1 year',
            'duration_days' => 365,
            'domain_limit' => 999,
            'activation_limit' => 1000,
            'price' => 499.00,
        ]);
    }
}
