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
    // Guard: satu fee aktif per client (valid_to IS NULL)
    Schema::table('client_admin_fees', function (Blueprint $table) {
        $table->unsignedBigInteger('active_lock')
            ->virtualAs('case when `valid_to` is null then `client_id` else null end')
            ->nullable();
        $table->unique('active_lock', 'uq_admin_fee_active_per_client');
    });

    // Guard: satu rekening primary per client
    Schema::table('client_bank_accounts', function (Blueprint $table) {
        $table->unsignedBigInteger('primary_lock')
            ->virtualAs('case when `is_primary` = 1 then `client_id` else null end')
            ->nullable();
        $table->unique('primary_lock', 'uq_bank_primary_per_client');
    });
}

public function down(): void
{
    Schema::table('client_bank_accounts', function (Blueprint $table) {
        $table->dropUnique('uq_bank_primary_per_client');
        $table->dropColumn('primary_lock');
    });
    Schema::table('client_admin_fees', function (Blueprint $table) {
        $table->dropUnique('uq_admin_fee_active_per_client');
        $table->dropColumn('active_lock');
    });
}
};
