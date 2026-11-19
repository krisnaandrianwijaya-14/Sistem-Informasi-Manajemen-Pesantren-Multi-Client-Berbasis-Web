<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicMasterSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan tabel terlebih dahulu
        DB::table('classes')->truncate();
        DB::table('subjects')->truncate();

        // Masukkan data Kelas
        DB::table('classes')->insert([
            ['id' => 1, 'tenant_id' => 1, 'name' => 'Kelas 1A - Ula', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'tenant_id' => 1, 'name' => 'Kelas 2B - Wustho', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Masukkan data Mapel
        DB::table('subjects')->insert([
            ['id' => 1, 'tenant_id' => 1, 'name' => 'Fiqih - Safinatun Najah', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'tenant_id' => 1, 'name' => 'Bahasa Arab - Jurumiyah', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Pastikan ada user guru
        DB::table('users')->insertOrIgnore([
            'id' => 1, 'name' => 'Ustadz Ahmad', 'email' => 'guru@test.com', 'password' => bcrypt('password'), 'role' => 'guru', 'created_at' => now(), 'updated_at' => now()
        ]);
    }
}