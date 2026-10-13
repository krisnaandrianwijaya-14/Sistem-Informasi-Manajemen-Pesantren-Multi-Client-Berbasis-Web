<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use App\Services\QrCodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Student extends Model
{
    use BelongsToClient;

    protected $fillable = [
        'client_id', 'name', 'student_number',
        'qr_token_display', 'qr_token_hash', 'qr_revoked_at',
        'qr_image_path', 'photo_path', 'status',
        'presence_status', 'last_check_in_at', 'last_check_out_at',
    ];

    protected $casts = [
        'last_check_in_at'  => 'datetime',
        'last_check_out_at' => 'datetime',
        'qr_revoked_at'     => 'datetime',
    ];

    // Hapus auto-generate di booted() — sekarang pakai StudentQrService::issue()
    // (biar hash + display token konsisten satu tempat).

    // ============ RELASI ============
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ============ HELPER ============

    /** QR santri aktif? */
    public function hasActiveQr(): bool
    {
        return $this->qr_token_hash !== null
            && $this->qr_revoked_at === null;
    }

    /** Bangun display token baru — SANTRI-{client_id}-{year}-{random}. */
    public static function buildDisplayToken(int $clientId): string
    {
        return sprintf(
            'SANTRI-%04d-%s-%s',
            $clientId,
            now()->format('Y'),
            strtoupper(Str::random(8)),
        );
    }

    public function qrImageUrl(): ?string
    {
        return $this->qr_image_path ? asset('storage/'.$this->qr_image_path) : null;
    }

    public function photoUrl(): ?string
    {
        return $this->photo_path ? asset('storage/'.$this->photo_path) : null;
    }

    public function isInside(): bool
    {
        return $this->presence_status === 'inside';
    }
}
