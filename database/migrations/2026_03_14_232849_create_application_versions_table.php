<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('application_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->string('version');
            $table->string('file_path');
            $table->bigInteger('file_size')->default(0);
            $table->text('release_notes')->nullable();
            $table->string('min_php_version')->nullable();
            $table->string('min_wp_version')->nullable();
            $table->timestamps();
            
            // A specific application cannot have two identical version strings
            $table->unique(['application_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_versions');
        Schema::dropIfExists('applications');
    }
};
