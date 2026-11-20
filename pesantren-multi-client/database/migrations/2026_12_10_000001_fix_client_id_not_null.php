<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ensure `client_id` on `students` is NOT NULL and has no default value.
 * The application sets the correct client via the ClientContext observer when
 * creating a student, so a default of 1 interferes with isolation tests.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Remove any default and enforce NOT NULL.
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        // Revert to nullable with default 1 (the previous state before this fix).
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NULL DEFAULT 1');
    }
};
