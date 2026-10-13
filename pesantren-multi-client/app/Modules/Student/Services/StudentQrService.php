<?php

namespace App\Modules\Student\Services;

use App\Models\Student;
use App\Services\QrCodeService;
use App\Support\Tokens\OpaqueToken;
use App\Modules\Client\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Modul 6 — Penerbitan & resolusi QR static santri.
 *
 * - Token opaque 32 byte (256-bit) — acak, tidak dapat ditebak.
 * - Format display: SANTRI-{client_id}-{year}-{random8}.
 * - Hash SHA-256 disimpan; token mentah HANYA dibuka sekali saat issue.
 * - QR static: TIDAK dirotasi otomatis. Revoke manual kalau bocor.
 */
final class StudentQrService
{
    public function __construct(private AuditLogger $audit) {}

    /**
     * Terbitkan QR untuk santri. Return: token MENTAH (untuk digambar jadi QR).
     *
     * @throws RuntimeException kalau sudah ada QR aktif & $force = false
     */
    public function issue(Student $student, bool $force = false): string
    {
        return DB::transaction(function () use ($student, $force): string {
            $locked = Student::query()->lockForUpdate()->findOrFail($student->id);

            if ($locked->hasActiveQr() && ! $force) {
                throw new RuntimeException(
                    'Santri sudah punya QR aktif. Pakai rotate() untuk ganti.'
                );
            }

            // Revoke QR lama (kalau ada)
            if ($locked->qr_token_hash !== null) {
                $locked->forceFill(['qr_revoked_at' => now()])->save();

                $this->audit->record(
                    modelType: 'student_qr',
                    modelId: $locked->id,
                    action: 'revoked',
                    before: ['display' => $locked->qr_token_display],
                    after: null,
                    clientId: $locked->client_id,
                );
            }

            // Generate token baru
            $token = OpaqueToken::issue(32);
            $display = Student::buildDisplayToken((int) $locked->client_id);

            $locked->forceFill([
                'qr_token_hash'    => $token['hash'],
                'qr_token_display' => $display,
                'qr_revoked_at'    => null,
            ])->save();

            // Generate QR image (URL lengkap, bukan cuma token)
            $url  = route('students.scan', ['token' => $token['plain']]);
            $path = app(QrCodeService::class)
                ->generateForStudent($url, $locked->student_number);

            $locked->forceFill(['qr_image_path' => $path])->save();

            $this->audit->record(
                modelType: 'student_qr',
                modelId: $locked->id,
                action: 'issued',
                before: null,
                after: ['display' => $display],
                clientId: $locked->client_id,
            );

            return $token['plain'];
        });
    }

    /** Rotasi = revoke + issue baru (hanya kalau QR bocor/hilang). */
    public function rotate(Student $student): string
    {
        return $this->issue($student, force: true);
    }

    /** Cari santri dari token mentah (untuk scan). */
    public function resolve(string $plainToken): ?Student
    {
        if ($plainToken === '' || $plainToken === '0') {
            return null;
        }

        return Student::query()
            ->where('qr_token_hash', OpaqueToken::hash($plainToken))
            ->whereNull('qr_revoked_at')
            ->first();
    }
}
