<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Update existing student records to have a valid client_id and enforce NOT NULL.
 * The demo seeder creates a client with id = 1, so we set that as default.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Ensure every student has a client_id (fallback to client id 1).
        DB::statement('UPDATE `students` SET `client_id` = 1 WHERE `client_id` IS NULL OR `client_id` = 0');

        // Alter column to be NOT NULL with default 1.
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NOT NULL DEFAULT 1');
    }

    public function down(): void
    {
        // Revert to nullable without default.
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NULL');
    }
};