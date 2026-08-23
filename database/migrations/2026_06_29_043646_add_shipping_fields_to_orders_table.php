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
        // These fields already exist in the original orders table migration.
        // Keep this migration as a no-op so the historical migration order stays valid
        // without attempting to add duplicate columns on fresh installs.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No schema changes were made in up().
    }
};
