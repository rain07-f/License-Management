<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to use raw SQL to update enum values easily or change to string
        DB::statement("ALTER TABLE license_logs MODIFY COLUMN action ENUM('generate', 'activate', 'validate', 'revoke', 'extend', 'deactivate', 'revoke_pair', 'revoke_domain', 'unbind_device', 'transfer', 'renew')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE license_logs MODIFY COLUMN action ENUM('generate', 'activate', 'validate', 'revoke', 'extend', 'deactivate')");
    }
};
