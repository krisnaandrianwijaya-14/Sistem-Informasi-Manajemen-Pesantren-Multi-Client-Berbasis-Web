<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AcademicMasterSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('classes')->truncate();
        DB::table('subjects')->truncate();
        Schema::enableForeignKeyConstraints();

        $classColumns = array_filter([
            'tenant_id' => Schema::hasColumn('classes', 'tenant_id') ? 1 : null,
            'client_id' => Schema::hasColumn('classes', 'client_id') ? 1 : null,
        ], fn ($v) => !is_null($v));

        DB::table('classes')->insert([
            array_merge(['id' => 1, 'name' => 'Kelas 1A - Ula', 'created_at' => now(), 'updated_at' => now()], $classColumns),
            array_merge(['id' => 2, 'name' => 'Kelas 2B - Wustho', 'created_at' => now(), 'updated_at' => now()], $classColumns),
        ]);

        $subjectColumns = array_filter([
            'tenant_id' => Schema::hasColumn('subjects', 'tenant_id') ? 1 : null,
            'client_id' => Schema::hasColumn('subjects', 'client_id') ? 1 : null,
        ], fn ($v) => !is_null($v));

        DB::table('subjects')->insert([
            array_merge(['id' => 1, 'name' => 'Fiqih - Safinatun Najah', 'created_at' => now(), 'updated_at' => now()], $subjectColumns),
            array_merge(['id' => 2, 'name' => 'Bahasa Arab - Jurumiyah', 'created_at' => now(), 'updated_at' => now()], $subjectColumns),
        ]);

        DB::table('users')->insertOrIgnore([
            'name' => 'Ustadz Ahmad',
            'email' => 'guru@test.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
