<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['wallets', 'wallet_transactions'] as $table) {
            if (Schema::hasColumn($table, 'client_id') && ! Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, fn (Blueprint $t) => $t->renameColumn('client_id', 'tenant_id'));
            }
        }
    }

    public function down(): void
    {
        foreach (['wallets', 'wallet_transactions'] as $table) {
            if (Schema::hasColumn($table, 'tenant_id') && ! Schema::hasColumn($table, 'client_id')) {
                Schema::table($table, fn (Blueprint $t) => $t->renameColumn('tenant_id', 'client_id'));
            }
        }
    }
};