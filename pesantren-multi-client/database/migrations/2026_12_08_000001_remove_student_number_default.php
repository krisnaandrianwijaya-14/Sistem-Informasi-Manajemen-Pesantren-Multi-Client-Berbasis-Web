<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Remove the empty string default from `student_number` and keep it nullable.
 * This prevents duplicate unique key errors when the column is omitted during
 * insertion; MySQL treats NULL as distinct for the composite unique index.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `students` MODIFY `student_number` VARCHAR(30) NOT NULL DEFAULT ""');
    }
};
