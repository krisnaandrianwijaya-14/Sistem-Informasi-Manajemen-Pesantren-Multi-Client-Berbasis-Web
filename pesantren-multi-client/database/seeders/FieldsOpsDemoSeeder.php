<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\Student;
use App\Models\StudentCard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/** Tambahan demo Orang C. Jalankan setelah FieldOpsSeeder: php artisan db:seed --class=FieldOpsDemoSeeder */
class FieldOpsDemoSeeder extends Seeder
{
    private function tcol(string $table): string
    {
        return Schema::hasColumn($table, 'client_id') ? 'client_id' : 'tenant_id';
    }

    private function insertGetFiltered(string $table, array $data): int
    {
        $now = now();
        $data += ['created_at' => $now, 'updated_at' => $now];

        return DB::table($table)->insertGetId(array_intersect_key($data, array_flip(Schema::getColumnListing($table))));
    }

    public function run(): void
    {
        $now = now();

        if (! DB::table('students')->where('nis', '2026001')->exists()) {
            $this->call(FieldOpsSeeder::class);
        }

        $admin = DB::table('users')->where('email', 'admin@alhikmah.test')->first();
        $clientId = ($admin ? DB::table('client_users')->where('user_id', $admin->id)->value('client_id') : null)
            ?? DB::table('clients')->value('id');

        $userIds = [];
        foreach ([
            ['kamtib@alhikmah.test', 'Petugas Kamtib', 'kamtib'],
            ['asrama@alhikmah.test', 'Wali Asrama', 'wali_asrama'],
        ] as [$email, $name, $role]) {
            $uid = DB::table('users')->where('email', $email)->value('id')
                ?? DB::table('users')->insertGetId([
                    'name' => $name, 'email' => $email, 'password' => Hash::make('password'),
                    'role' => $role, 'email_verified_at' => $now, 'created_at' => $now, 'updated_at' => $now,
                ]);
            $userIds[$role] = $uid;
            DB::table('client_users')->updateOrInsert(
                ['client_id' => $clientId, 'user_id' => $uid],
                ['role' => $role, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $students = DB::table('students')->where('tenant_id', $clientId)
            ->whereIn('nis', ['2026001', '2026002', '2026003'])->orderBy('nis')->get();

        // Asrama, kamar, penempatan (kolom diisi hanya yang ada di tabel)
        try {
            $dc = $this->tcol('dormitories');
            $dormId = DB::table('dormitories')->where($dc, $clientId)->value('id')
                ?? $this->insertGetFiltered('dormitories', [
                    'client_id' => $clientId, 'tenant_id' => $clientId, 'name' => 'Asrama Putra A', 'gender' => 'putra',
                ]);
            $rc = $this->tcol('rooms');
            $roomId = DB::table('rooms')->where($rc, $clientId)->value('id')
                ?? $this->insertGetFiltered('rooms', [
                    'client_id' => $clientId, 'tenant_id' => $clientId, 'dormitory_id' => $dormId,
                    'name' => 'Kamar 1', 'capacity' => 10,
                ]);
            foreach ($students as $s) {
                DB::table('room_assignments')->updateOrInsert(
                    ['client_id' => $clientId, 'student_id' => $s->id, 'is_active' => 1],
                    ['room_id' => $roomId, 'start_date' => now()->toDateString(), 'created_at' => $now, 'updated_at' => $now]
                );
            }
        } catch (\Throwable $e) {
            $this->command->warn('Asrama/kamar gagal di-seed: ' . $e->getMessage());
        }

        // Kartu QR
        foreach ($students as $s) {
            $card = StudentCard::where('student_id', $s->id)->where('status', 'active')->first()
                ?? StudentCard::issue(Student::withoutGlobalScopes()->find($s->id));
            $this->command->line("Kartu {$s->nis} {$s->full_name}: {$card->token}");
        }

        // Izin demo: santri ke-2 sudah diajukan, menunggu scan Kamtib
        $second = $students->get(1);
        if ($second) {
            LeaveRequest::updateOrCreate(['code' => 'IZN-DEMO-001'], [
                'client_id' => $clientId, 'student_id' => $second->id, 'type' => 'pulang',
                'reason' => 'Acara keluarga', 'start_time' => now(), 'end_time' => now()->addHours(6),
                'deadline' => now()->addHours(6), 'status' => 'menunggu_ttd_offline',
                'issued_by' => $userIds['wali_asrama'],
            ]);
        }

        $this->command->info('Akun: kamtib@alhikmah.test, asrama@alhikmah.test, kasir@alhikmah.test (password: password)');
    }
}
