<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adjust existing `students` table columns to ensure required default values.
 * This migration fixes cases where the column was created earlier without a default.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Ensure client_id has default 1
            if (Schema::hasColumn('students', 'client_id')) {
                $table->foreignId('client_id')->default(1)->change();
            }
            // Ensure tenant_id has default 1
            if (Schema::hasColumn('students', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->default(1)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove defaults (set to nullable without default)
            if (Schema::hasColumn('students', 'client_id')) {
                $table->foreignId('client_id')->nullable()->change();
            }
            if (Schema::hasColumn('students', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->change();
            }
        });
    }
};
