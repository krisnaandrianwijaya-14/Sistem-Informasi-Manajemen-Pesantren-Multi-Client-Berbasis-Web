<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ensure `student_number` column is nullable without a default value.
 * Existing empty strings are converted to NULL, then the column is altered.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Convert empty strings to NULL to avoid duplicate '' values.
        DB::statement('UPDATE `students` SET `student_number` = NULL WHERE `student_number` = ""');
        // Alter column to be nullable with no default.
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NULL');
    }

    public function down(): void
    {
        // Revert: make column NOT NULL (will set NULLs to empty string for safety).
        DB::statement('UPDATE `students` SET `student_number` = "" WHERE `student_number` IS NULL');
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NOT NULL');
    }
};
