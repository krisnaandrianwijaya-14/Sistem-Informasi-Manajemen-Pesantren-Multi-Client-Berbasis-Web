<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Clean up `student_number` column:
 *   1. Convert existing empty strings to NULL.
 *   2. Alter the column to be nullable without a default value.
 * This resolves duplicate‑key errors caused by the previous migration that
 * introduced a default empty string.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Convert empty strings to NULL.
        DB::statement('UPDATE `students` SET `student_number` = NULL WHERE `student_number` = ""');

        // Step 2: Change column definition.
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_number', 30)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        // Revert: set column back to NOT NULL with empty string default.
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_number', 30)->nullable(false)->default('')->change();
        });
        // Ensure any NULL values become empty strings again.
        DB::statement('UPDATE `students` SET `student_number` = "" WHERE `student_number` IS NULL');
    }
};
