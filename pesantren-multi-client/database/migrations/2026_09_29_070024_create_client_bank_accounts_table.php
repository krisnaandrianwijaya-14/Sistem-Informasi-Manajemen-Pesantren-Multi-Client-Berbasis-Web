<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('client_bank_accounts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained()->cascadeOnDelete();
    $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
    $table->text('account_number_cipher'); // encrypted
    $table->string('account_last4', 4);
    $table->string('bank_code', 20);
    $table->string('account_holder', 120);
    $table->string('status', 20)->default('unverified'); // unverified/verified/rejected
    $table->boolean('is_primary')->default(false);
    $table->timestamps(6);

    $table->unique(['client_id', 'id']);
    $table->index(['client_id', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_bank_accounts');
    }
};
