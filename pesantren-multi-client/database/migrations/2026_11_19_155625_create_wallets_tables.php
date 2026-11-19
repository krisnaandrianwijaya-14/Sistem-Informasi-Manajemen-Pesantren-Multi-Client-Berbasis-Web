<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('client_id')->index();   // GANTI jika kolom tenant bukan client_id
            $t->unsignedBigInteger('student_id');
            $t->bigInteger('balance')->default(0);           // rupiah utuh, bukan float
            $t->timestamps();
            $t->unique(['client_id', 'student_id']);
        });

        Schema::create('wallet_transactions', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('client_id')->index();
            $t->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $t->string('type', 20);                          // topup | debit | reversal
            $t->string('status', 20)->default('success');    // success | pending | failed
            $t->unsignedBigInteger('amount');
            $t->bigInteger('balance_after')->nullable();
            $t->string('idempotency_key')->unique();
            $t->string('reference')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->timestamps();
            $t->index(['wallet_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
    }
};