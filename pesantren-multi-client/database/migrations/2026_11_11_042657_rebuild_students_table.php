<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel lama yang strukturnya salah
        Schema::dropIfExists('students');

        // Buat tabel baru dengan semua kolom yang dibutuhkan form
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->default(1); // Pakai integer biasa agar tidak error FK
            $table->string('nis')->unique();
            $table->string('full_name');
            $table->string('gender');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->string('dormitory_name')->nullable();
            $table->string('room_number')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};