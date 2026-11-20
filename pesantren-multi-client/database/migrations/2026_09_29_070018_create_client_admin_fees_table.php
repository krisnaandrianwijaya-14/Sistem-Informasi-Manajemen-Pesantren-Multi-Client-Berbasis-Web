<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_admin_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('fee_per_student'); // Rupiah / santri / bulan
            $table->timestampTz('valid_from', 6);
            $table->timestampTz('valid_to', 6)->nullable();
            $table->timestamps(6);

            $table->unique(['client_id', 'id']);
            $table->index(['client_id', 'valid_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_admin_fees');
    }
};
