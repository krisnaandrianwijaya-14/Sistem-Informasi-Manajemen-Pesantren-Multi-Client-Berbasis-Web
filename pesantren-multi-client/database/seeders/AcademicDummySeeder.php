<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AcademicDummySeeder extends Seeder
{
    public function run(): void
    {
        // Buat Kelas
        Classes::firstOrCreate(['name' => 'Kelas 1A - Ula']);
        Classes::firstOrCreate(['name' => 'Kelas 2B - Wustho']);

        // Buat Mapel
        Subject::firstOrCreate(['name' => 'Fiqih - Safinatun Najah']);
        Subject::firstOrCreate(['name' => 'Bahasa Arab - Jurumiyah']);

        // Buat Akun Guru
        User::firstOrCreate(
            ['email' => 'guru@alhikmah.test'],
            [
                'name' => 'Ustadz Ahmad Fauzi',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
    }
}