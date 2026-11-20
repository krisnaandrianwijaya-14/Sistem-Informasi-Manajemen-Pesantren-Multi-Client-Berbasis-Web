<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify existing students table – add missing columns only.
        Schema::table('students', function (Blueprint $table) {
            // Add client_id with default 1 if it does not exist.
            if (!Schema::hasColumn('students', 'client_id')) {
                $table->foreignId('client_id')
                      ->default(1)
                      ->constrained()
                      ->restrictOnDelete()
                      ->after('id');
            }

            if (!Schema::hasColumn('students', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')
                      ->default(1)
                      ->after('id');
            }

            if (!Schema::hasColumn('students', 'nis')) {
                $table->string('nis')
                      ->unique()
                      ->nullable()
                      ->after('tenant_id');
            }

            if (!Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')
                      ->nullable()
                      ->after('nis');
            }

            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender')
                      ->nullable()
                      ->after('full_name');
            }

            if (!Schema::hasColumn('students', 'birth_place')) {
                $table->string('birth_place')
                      ->nullable()
                      ->after('gender');
            }

            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')
                      ->nullable()
                      ->after('birth_place');
            }

            if (!Schema::hasColumn('students', 'class_id')) {
                $table->unsignedBigInteger('class_id')
                      ->nullable()
                      ->after('birth_date');
            }

            if (!Schema::hasColumn('students', 'dormitory_name')) {
                $table->string('dormitory_name')
                      ->nullable()
                      ->after('class_id');
            }

            if (!Schema::hasColumn('students', 'room_number')) {
                $table->string('room_number')
                      ->nullable()
                      ->after('dormitory_name');
            }

            if (!Schema::hasColumn('students', 'father_name')) {
                $table->string('father_name')
                      ->nullable()
                      ->after('room_number');
            }

            if (!Schema::hasColumn('students', 'mother_name')) {
                $table->string('mother_name')
                      ->nullable()
                      ->after('father_name');
            }

            if (!Schema::hasColumn('students', 'phone_number')) {
                $table->string('phone_number')
                      ->nullable()
                      ->after('mother_name');
            }

            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')
                      ->nullable()
                      ->after('phone_number');
            }

            if (!Schema::hasColumn('students', 'qr_token')) {
                $table->string('qr_token')
                      ->nullable()
                      ->after('address');
            }

            if (!Schema::hasColumn('students', 'status')) {
                $table->string('status')
                      ->default('active')
                      ->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'client_id', 'tenant_id', 'nis', 'full_name', 'gender',
                'birth_place', 'birth_date', 'class_id', 'dormitory_name',
                'room_number', 'father_name', 'mother_name', 'phone_number',
                'address', 'qr_token', 'status',
            ]);
        });
    }
};
