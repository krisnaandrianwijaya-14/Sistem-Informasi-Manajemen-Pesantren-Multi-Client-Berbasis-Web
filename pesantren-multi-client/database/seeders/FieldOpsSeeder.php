<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FieldOpsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $admin = DB::table('users')->where('email', 'admin@alhikmah.test')->first();
        $clientId = DB::table('client_users')->where('user_id', $admin->id)->value('client_id')
            ?? DB::table('clients')->value('id');

        // Akun kasir
        $kasirId = DB::table('users')->where('email', 'kasir@alhikmah.test')->value('id')
            ?? DB::table('users')->insertGetId([
                'name' => 'Kasir Kantin',
                'email' => 'kasir@alhikmah.test',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        DB::table('client_users')->updateOrInsert(
            ['client_id' => $clientId, 'user_id' => $kasirId],
            ['role' => 'kasir', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now]
        );

        // Santri demo + saldo
        foreach ([['2026001', 'Ahmad Fauzi'], ['2026002', 'Muhammad Rizki'], ['2026003', 'Abdullah Hakim']] as [$nis, $nama]) {
            DB::table('students')->updateOrInsert(
                ['nis' => $nis],
                ['tenant_id' => $clientId, 'full_name' => $nama, 'gender' => 'putra',
                 'status' => 'active', 'created_at' => $now, 'updated_at' => $now]
            );

            $studentId = DB::table('students')->where('nis', $nis)->value('id');

            DB::table('wallets')->updateOrInsert(
                ['tenant_id' => $clientId, 'student_id' => $studentId],
                ['balance' => 100000, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $this->command->info('Akun kasir: kasir@alhikmah.test / password');
    }
}