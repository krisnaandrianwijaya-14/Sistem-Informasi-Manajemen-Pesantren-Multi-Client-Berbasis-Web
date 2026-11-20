<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PesantrenDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan tabel tenants & canteens bawaan branch absensi tetap terisi (ID = 1)
        if (Schema::hasTable('canteens') && Schema::hasTable('tenants')) {
            $canteenId = DB::table('canteens')->where('name', 'Pesantren Al-Hikmah')->value('id')
                ?? DB::table('canteens')->insertGetId([
                    'name' => 'Pesantren Al-Hikmah',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            DB::table('tenants')->updateOrInsert(
                ['id' => 1],
                [
                    'canteen_id' => $canteenId,
                    'name' => 'Pesantren Al-Hikmah',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Bikin Client Pesantren untuk gerbang login & URL /{slug}/dashboard
        $client = Client::firstOrCreate(
            ['slug' => 'al-hikmah'],
            [
                'name' => 'Pesantren Al-Hikmah',
                'status' => 'active',
            ]
        );

        // 3. Akun Admin Pesantren (password: password) -> masuk ke /al-hikmah/dashboard
        $adminPesantren = User::updateOrCreate(
            ['email' => 'admin@alhikmah.pesantren.id'],
            [
                'name' => 'Admin Pesantren Al-Hikmah',
                'password' => Hash::make('password'),
                'role' => 'client_admin',
                'email_verified_at' => now(),
            ]
        );
        $adminPesantren->clients()->syncWithoutDetaching([
            $client->id => ['role' => 'admin_pesantren'],
        ]);

        // 4. Akun Guru (password: password) -> masuk ke /al-hikmah/guru/dashboard
        $guru = User::updateOrCreate(
            ['email' => 'guru@alhikmah.pesantren.id'],
            [
                'name' => 'Ustadz Ahmad Fauzi',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'email_verified_at' => now(),
            ]
        );
        $guru->clients()->syncWithoutDetaching([
            $client->id => ['role' => 'guru'],
        ]);
    }
}
