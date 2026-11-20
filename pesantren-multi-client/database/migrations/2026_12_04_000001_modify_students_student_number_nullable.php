<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Make `student_number` column nullable with a default empty string.
 * This satisfies tests that insert a student without specifying this field.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_number', 30)->nullable()->default('')->change();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_number', 30)->nullable(false)->default(null)->change();
        });
    }
};
