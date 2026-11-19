<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\StudentCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Orang C: scanner Kamtib (keluar/kembali), Wali Asrama (absensi + buat izin).
 * Memakai kartu QR (StudentCard) dan alur status izin yang sudah ada di tim:
 * menunggu_ttd_offline -> izin_aktif -> kembali_selesai.
 */
class FieldOpsController extends Controller
{
    private const KAMTIB_ROLES = ['kamtib', 'admin_pesantren'];
    private const ASRAMA_ROLES = ['wali_asrama', 'admin_pesantren'];

    // ---------- helper ----------

    private function tenantId(Request $r): int
    {
        return (int) DB::table('client_users')->where('user_id', $r->user()->id)->value('client_id');
    }

    private function allow(Request $r, array $roles): void
    {
        abort_unless(in_array($r->user()->role, $roles, true), 403);
    }

    private function fail(string $msg, int $code = 422): JsonResponse
    {
        return response()->json(['ok' => false, 'message' => $msg], $code);
    }

    private function done(string $msg, array $extra = []): JsonResponse
    {
        return response()->json(['ok' => true, 'message' => $msg] + $extra);
    }

    private function resolveStudent(Request $r, string $code): ?object
    {
        $card = StudentCard::where('token', $code)
            ->where('status', 'active')
            ->where('tenant_id', $this->tenantId($r))
            ->first();

        return $card
            ? DB::table('students')->where('id', $card->student_id)->whereNull('deleted_at')->first()
            : null;
    }

    /** Insert hanya kolom yang benar-benar ada di tabel (skema tim sempat berubah-ubah). */
    private function insertFiltered(string $table, array $data): void
    {
        $now = now();
        $data += ['created_at' => $now, 'updated_at' => $now];
        DB::table($table)->insert(array_intersect_key($data, array_flip(Schema::getColumnListing($table))));
    }

    /** Pilih nilai enum yang ada di DB (urut kesukaan); kalau bukan enum pakai pilihan pertama. */
    private function pick(string $table, string $col, array $prefer): string
    {
        $row = DB::selectOne("SHOW COLUMNS FROM `{$table}` LIKE '{$col}'");
        if ($row && preg_match('/^enum\((.*)\)$/i', $row->Type, $m)) {
            $vals = array_map(fn ($v) => trim($v, "'"), explode(',', $m[1]));
            foreach ($prefer as $p) {
                if (in_array($p, $vals, true)) {
                    return $p;
                }
            }

            return $vals[0];
        }

        return $prefer[0];
    }

    private function sessionName(): string
    {
        $prefer = now()->hour < 12 ? ['pagi', 'subuh', 'morning'] : ['malam', 'sore', 'night'];

        return $this->pick('dorm_attendances', 'session', $prefer);
    }

    // ---------- halaman ----------

    public function kamtibPage(Request $r)
    {
        $this->allow($r, self::KAMTIB_ROLES);

        return view('scan.kamtib');
    }

    public function asramaPage(Request $r)
    {
        $this->allow($r, self::ASRAMA_ROLES);

        return view('scan.asrama');
    }

    // ---------- Kamtib: scan keluar / kembali ----------

    public function kamtibScan(Request $r): JsonResponse
    {
        $this->allow($r, self::KAMTIB_ROLES);
        $code = $r->validate(['code' => 'required|string|max:100'])['code'];
        $tenant = $this->tenantId($r);

        $student = $this->resolveStudent($r, $code);
        if (! $student) {
            return $this->fail('Kartu tidak valid atau diblokir.', 404);
        }

        return DB::transaction(function () use ($r, $tenant, $student) {
            $permit = LeaveRequest::where('client_id', $tenant)
                ->where('student_id', $student->id)
                ->whereIn('status', ['izin_aktif', 'menunggu_ttd_offline'])
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if (! $permit) {
                return $this->fail('IZIN TIDAK SAH: ' . $student->full_name . ' tidak punya izin.');
            }

            $now = now();

            // Scan pertama di Kamtib: santri keluar.
            if ($permit->status === 'menunggu_ttd_offline') {
                if ($permit->deadline && $now->greaterThan($permit->deadline)) {
                    return $this->fail('IZIN KEDALUWARSA: ' . $student->full_name);
                }

                $permit->update([
                    'status' => 'izin_aktif',
                    'departed_at' => $now,
                    'activated_by' => $r->user()->id,
                ]);

                return $this->done('KELUAR: ' . $student->full_name, [
                    'action' => 'KELUAR',
                    'code' => $permit->code,
                    'deadline' => optional($permit->deadline)->format('d M Y H:i'),
                ]);
            }

            // Scan berikutnya: santri kembali.
            $isLate = $permit->deadline && $now->greaterThan($permit->deadline);
            $lateMinutes = $isLate ? (int) round($permit->deadline->diffInMinutes($now)) : 0;

            $permit->update([
                'status' => 'kembali_selesai',
                'returned_at' => $now,
                'is_late' => $isLate,
                'late_minutes' => $lateMinutes,
                'completed_by' => $r->user()->id,
            ]);

            if ($isLate) {
                $this->insertFiltered('permit_violations', [
                    'client_id' => $tenant,
                    'student_id' => $student->id,
                    'leave_request_id' => $permit->id,
                    'late_minutes' => $lateMinutes,
                    'notes' => "Terlambat {$lateMinutes} menit dari batas " . $permit->deadline->format('d M Y H:i'),
                    'recorded_at' => $now,
                    'recorded_by' => $r->user()->id,
                ]);
            }

            return $this->done(
                ($isLate ? "KEMBALI TERLAMBAT {$lateMinutes} menit: " : 'KEMBALI TEPAT WAKTU: ') . $student->full_name,
                ['action' => 'KEMBALI', 'late' => $isLate, 'code' => $permit->code]
            );
        });
    }

    // ---------- Wali Asrama: absensi ----------

    public function asramaAbsensi(Request $r): JsonResponse
    {
        $this->allow($r, self::ASRAMA_ROLES);
        $code = $r->validate(['code' => 'required|string|max:100'])['code'];
        $tenant = $this->tenantId($r);

        $student = $this->resolveStudent($r, $code);
        if (! $student) {
            return $this->fail('Kartu tidak valid atau diblokir.', 404);
        }

        $assignment = DB::table('room_assignments')
            ->where('client_id', $tenant)
            ->where('student_id', $student->id)
            ->where('is_active', 1)
            ->first();
        if (! $assignment) {
            return $this->fail($student->full_name . ' belum punya kamar aktif.');
        }

        $date = now()->toDateString();
        $session = $this->sessionName();
        $hadir = $this->pick('dorm_attendances', 'status', ['hadir', 'present']);

        $already = DB::table('dorm_attendances')
            ->where('client_id', $tenant)->where('student_id', $student->id)
            ->where('date', $date)->where('session', $session)
            ->exists();

        if (! $already) {
            $this->insertFiltered('dorm_attendances', [
                'client_id' => $tenant,
                'student_id' => $student->id,
                'room_id' => $assignment->room_id,
                'session' => $session,
                'status' => $hadir,
                'date' => $date,
                'recorded_by' => $r->user()->id,
            ]);
        }

        $total = DB::table('room_assignments')->where('client_id', $tenant)->where('is_active', 1)
            ->distinct()->count('student_id');
        $done = DB::table('dorm_attendances')->where('client_id', $tenant)->where('date', $date)
            ->where('session', $session)->where('status', $hadir)->distinct()->count('student_id');

        return $this->done(
            ($already ? 'Sudah tercatat: ' : 'HADIR: ') . $student->full_name,
            ['progress' => "{$done}/{$total}"]
        );
    }

    // ---------- Wali Asrama: buat izin (sakit / pulang / keluar / lainnya) ----------

    public function asramaIzin(Request $r): JsonResponse
    {
        $this->allow($r, self::ASRAMA_ROLES);
        $d = $r->validate([
            'code' => 'required|string|max:100',
            'type' => 'required|in:pulang,keluar,sakit,lainnya',
            'reason' => 'required|string|max:500',
            'deadline' => 'nullable|date|after:now',
            'destination' => 'nullable|string|max:200',
        ]);

        if ($d['type'] !== 'sakit' && empty($d['deadline'])) {
            return $this->fail('Batas kembali wajib diisi untuk izin ini.');
        }

        $tenant = $this->tenantId($r);
        $student = $this->resolveStudent($r, $d['code']);
        if (! $student) {
            return $this->fail('Kartu tidak valid atau diblokir.', 404);
        }

        return DB::transaction(function () use ($r, $d, $tenant, $student) {
            $active = LeaveRequest::where('client_id', $tenant)
                ->where('student_id', $student->id)
                ->whereIn('status', ['menunggu_ttd_offline', 'izin_aktif'])
                ->lockForUpdate()->exists();
            if ($active) {
                return $this->fail($student->full_name . ' masih punya izin aktif.', 409);
            }

            $isSakit = $d['type'] === 'sakit';
            $deadline = ! empty($d['deadline']) ? Carbon::parse($d['deadline']) : now()->addDay();

            $permit = LeaveRequest::create([
                'client_id' => $tenant,
                'student_id' => $student->id,
                'code' => 'IZN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'type' => $d['type'],
                'reason' => $d['reason'],
                'start_time' => now(),
                'end_time' => $deadline,
                'deadline' => $deadline,
                'destination' => $d['destination'] ?? null,
                // Sakit langsung aktif (tanpa persetujuan berjenjang); lainnya menunggu scan Kamtib.
                'status' => $isSakit ? 'izin_aktif' : 'menunggu_ttd_offline',
                'issued_by' => $r->user()->id,
                'activated_by' => $isSakit ? $r->user()->id : null,
            ]);

            return $this->done(
                ($isSakit ? 'IZIN SAKIT AKTIF: ' : 'Izin dibuat, menunggu scan Kamtib: ') . $student->full_name,
                ['code' => $permit->code]
            );
        });
    }
}
