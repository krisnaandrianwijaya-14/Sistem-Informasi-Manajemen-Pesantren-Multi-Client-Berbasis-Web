<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('birth_place')->nullable()->after('gender');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->foreignId('class_id')->nullable()->after('birth_date');
            $table->string('dormitory_name')->nullable()->after('class_id');
            $table->string('room_number')->nullable()->after('dormitory_name');
            $table->string('father_name')->nullable()->after('room_number');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('phone_number')->nullable()->after('mother_name');
            $table->text('address')->nullable()->after('phone_number');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['birth_place', 'birth_date', 'class_id', 'dormitory_name', 'room_number', 'father_name', 'mother_name', 'phone_number', 'address']);
        });
    }
};