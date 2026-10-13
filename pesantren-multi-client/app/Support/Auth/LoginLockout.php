<?php

namespace App\Support\Auth;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * UC-19 alur 2b — Penguncian akun.
 *
 * Lebih dari 5× gagal masuk dalam 10 menit mengunci akun selama 15 menit.
 * Kunci dihitung per AKUN (hash surel), bukan per IP, sehingga berpindah
 * IP tidak mereset penghitung. Penyimpanan: cache store aplikasi (Redis).
 */
final class LoginLockout
{
    public const MAX_FAILURES = 5;
    public const FAILURE_WINDOW_SECONDS = 600;   // 10 menit
    public const LOCK_SECONDS = 900;             // 15 menit

    public function isLocked(string $email): bool
    {
        return $this->lockedUntil($email) > now()->getTimestamp();
    }

    /** Sisa waktu kunci dalam menit (dibulatkan ke atas). */
    public function minutesRemaining(string $email): int
    {
        $seconds = max(0, $this->lockedUntil($email) - now()->getTimestamp());

        return max(1, (int) ceil($seconds / 60));
    }

    /** Catat satu kegagalan; return true bila ini memicu penguncian. */
    public function recordFailure(string $email): bool
    {
        $now = now()->getTimestamp();

        /** @var array{count: int, started_at: int}|null $window */
        $window = Cache::get($this->counterKey($email));

        if ($window === null || $now - $window['started_at'] >= self::FAILURE_WINDOW_SECONDS) {
            $window = ['count' => 0, 'started_at' => $now];
        }

        $window['count']++;

        if ($window['count'] < self::MAX_FAILURES) {
            Cache::put($this->counterKey($email), $window, self::FAILURE_WINDOW_SECONDS);

            return false;
        }

        Cache::put($this->lockKey($email), $now + self::LOCK_SECONDS, self::LOCK_SECONDS);
        Cache::forget($this->counterKey($email));

        return true;
    }

    public function clear(string $email): void
    {
        Cache::forget($this->counterKey($email));
        Cache::forget($this->lockKey($email));
    }

    private function lockedUntil(string $email): int
    {
        return (int) Cache::get($this->lockKey($email), 0);
    }

    private function counterKey(string $email): string
    {
        return 'login-failures:'.$this->fingerprint($email);
    }

    private function lockKey(string $email): string
    {
        return 'login-lock:'.$this->fingerprint($email);
    }

    private function fingerprint(string $email): string
    {
        return hash('sha256', Str::lower(trim($email)));
    }
}
