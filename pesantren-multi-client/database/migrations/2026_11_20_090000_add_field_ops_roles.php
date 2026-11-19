<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Menambah role kamtib/kasir/wali_asrama ke kolom role HANYA jika kolomnya bertipe enum.
return new class extends Migration
{
    public function up(): void
    {
        foreach (['users', 'client_users'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'role')) {
                continue;
            }
            $row = DB::selectOne("SHOW COLUMNS FROM `{$table}` LIKE 'role'");
            if (! $row || ! preg_match('/^enum\((.*)\)$/i', $row->Type, $m)) {
                continue;
            }
            $vals = array_map(fn ($v) => trim($v, "'"), explode(',', $m[1]));
            $missing = array_diff(['kamtib', 'kasir', 'wali_asrama'], $vals);
            if (! $missing) {
                continue;
            }
            $all = implode(',', array_map(fn ($v) => "'" . str_replace("'", "''", $v) . "'", array_merge($vals, $missing)));
            $null = $row->Null === 'YES' ? 'NULL' : 'NOT NULL';
            $default = $row->Default !== null ? " DEFAULT '" . str_replace("'", "''", $row->Default) . "'" : '';
            DB::statement("ALTER TABLE `{$table}` MODIFY `role` ENUM({$all}) {$null}{$default}");
        }
    }

    public function down(): void
    {
        // sengaja kosong: tidak mengurangi nilai enum agar data tidak rusak
    }
};
