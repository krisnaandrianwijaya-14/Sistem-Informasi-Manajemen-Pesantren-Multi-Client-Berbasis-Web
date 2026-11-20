<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adjust `student_number` column to be nullable without a default value.
 * This prevents duplicate unique key errors when the column is omitted during
 * record creation; MySQL treats NULL as distinct for the composite unique index.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Modify column: allow NULL and remove any default.
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NULL');
    }

    public function down(): void
    {
        // Revert to original: NOT NULL without default.
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NOT NULL');
    }
};
