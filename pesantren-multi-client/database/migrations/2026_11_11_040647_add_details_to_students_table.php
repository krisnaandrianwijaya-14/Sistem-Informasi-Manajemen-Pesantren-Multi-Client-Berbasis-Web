<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender', 20)->nullable();
            }
            if (!Schema::hasColumn('students', 'birth_place')) {
                $table->string('birth_place')->nullable();
            }
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
            if (!Schema::hasColumn('students', 'class_id')) {
                $table->foreignId('class_id')->nullable();
            }
            if (!Schema::hasColumn('students', 'dormitory_name')) {
                $table->string('dormitory_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'room_number')) {
                $table->string('room_number')->nullable();
            }
            if (!Schema::hasColumn('students', 'father_name')) {
                $table->string('father_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'mother_name')) {
                $table->string('mother_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columns = array_filter(
                [
                    'gender',
                    'birth_place',
                    'birth_date',
                    'class_id',
                    'dormitory_name',
                    'room_number',
                    'father_name',
                    'mother_name',
                    'phone_number',
                    'address',
                ],
                fn ($col) => Schema::hasColumn('students', $col)
            );

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
