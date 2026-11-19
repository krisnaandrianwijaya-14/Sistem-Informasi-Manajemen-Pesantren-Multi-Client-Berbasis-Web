<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantDummySeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 Canteen/Pesantren jika belum ada
        $canteenId = DB::table('canteens')->insertGetId([
            'name' => 'Pesantren Al-Hikmah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat 1 Tenant (Pesantren) dengan ID 1
        DB::table('tenants')->insert([
            'id' => 1,
            'canteen_id' => $canteenId,
            'name' => 'Pesantren Al-Hikmah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}