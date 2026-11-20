<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Final adjustments for the `students` table:
 *   1. Convert empty `student_number` values to NULL.
 *   2. Alter `student_number` to be nullable with no default.
 *   3. Ensure `client_id` is NOT NULL with a default of 1.
 * This resolves duplicate‑key errors and restores the expected default client.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Clean existing empty strings.
        DB::statement('UPDATE `students` SET `student_number` = NULL WHERE `student_number` = ""');

        // 2. Make `student_number` nullable without a default.
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NULL');

        // 3. Set `client_id` to NOT NULL with default 1.
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NOT NULL DEFAULT 1');
    }

    public function down(): void
    {
        // Revert changes if needed.
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NOT NULL');
        DB::statement('UPDATE `students` SET `student_number` = "" WHERE `student_number` IS NULL');
    }
};
