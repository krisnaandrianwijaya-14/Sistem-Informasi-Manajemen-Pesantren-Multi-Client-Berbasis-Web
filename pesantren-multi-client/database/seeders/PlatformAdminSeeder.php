<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PlatformAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Kita gunakan firstOrCreate agar jika dijalankan 2x tidak error (duplikat)
        User::firstOrCreate(
            ['email' => 'superadmin@pesantren.id'], // Cek berdasarkan email
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Passwordnya: password
                'role' => 'platform_admin', // Ini kunci sebagai pemilik aplikasi
            ]
        );
    }
}
