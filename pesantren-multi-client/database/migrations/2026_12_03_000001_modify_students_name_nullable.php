<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Make the `name` column on `students` nullable with a default empty string.
 * This resolves tests that insert a student without specifying a name.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('name', 120)->nullable()->default('')->change();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('name', 120)->nullable(false)->default(null)->change();
        });
    }
};
