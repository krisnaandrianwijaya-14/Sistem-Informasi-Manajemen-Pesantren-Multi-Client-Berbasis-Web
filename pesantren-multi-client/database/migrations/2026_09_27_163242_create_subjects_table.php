<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
    $table->unsignedBigInteger('client_id')->nullable()->default(1);
    $table->string('name');
    // ... biarkan kolom lain yang sudah ada di file ini ...
    $table->timestamps();

    $table->unique(['client_id', 'id']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
