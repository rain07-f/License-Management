<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // How many active pairs this license allows
            $table->integer('activation_quota')->default(1)->after('license_key_display');

            // Indexes requested
            $table->index('status');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['expires_at']);
            $table->dropColumn('activation_quota');
        });
    }
};
