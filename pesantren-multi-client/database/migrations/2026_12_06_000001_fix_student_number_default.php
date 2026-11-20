<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ensure `student_number` column on `students` table is nullable with a default empty string.
 * This matches test expectations where a student may be created without specifying a number.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Modify column directly via raw SQL to avoid needing doctrine/dbal.
        DB::statement("ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NULL DEFAULT ''");
    }

    public function down(): void
    {
        // Revert to original definition: NOT NULL without default.
        DB::statement("ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NOT NULL");
    }
};
