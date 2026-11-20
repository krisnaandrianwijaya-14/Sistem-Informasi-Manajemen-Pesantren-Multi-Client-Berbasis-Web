<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Modul 6 — Upgrade keamanan QR santri.
 *
 * - Rename `qr_token` → `qr_token_display` (data existing dipertahankan, jadi
 *   display token untuk admin/kartu; bukan untuk scan).
 * - Tambah `qr_token_hash` (binary 32) — hash SHA-256 untuk verifikasi scan.
 * - Backfill hash dari token existing supaya QR cetak lama tetap valid.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Rename qr_token → qr_token_display
        Schema::table('students', function (Blueprint $table): void {
            $table->renameColumn('qr_token', 'qr_token_display');
        });

        // 2. Tambah kolom hash + metadata
        Schema::table('students', function (Blueprint $table): void {
            $table->binary('qr_token_hash', 32)->nullable()->after('qr_token_display');
            $table->timestampTz('qr_revoked_at', 6)->nullable()->after('qr_token_hash');
        });

        // 3. Backfill hash dari token existing
        DB::table('students')
            ->whereNotNull('qr_token_display')
            ->orderBy('id')
            ->each(function ($student): void {
                DB::table('students')
                    ->where('id', $student->id)
                    ->update([
                        'qr_token_hash' => hash('sha256', $student->qr_token_display, true),
                    ]);
            });

        // 4. Unique constraint pada hash
        Schema::table('students', function (Blueprint $table): void {
            $table->unique('qr_token_hash');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->dropUnique(['qr_token_hash']);
            $table->dropColumn(['qr_token_hash', 'qr_revoked_at']);
            $table->renameColumn('qr_token_display', 'qr_token');
        });
    }
};
