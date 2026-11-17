<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\LoginViewResponse;
use App\Http\Responses\LogoutResponse;
use App\Models\User;
use App\Support\Auth\LoginLockout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LoginViewResponse as LoginViewResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoginViewResponseContract::class, LoginViewResponse::class);
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);
    }

    public function boot(): void
    {
        Fortify::loginView(fn () => view('auth.login'));

        // UC-19 — Verifikasi kredensial + status akun + penguncian + pesan generik.
        Fortify::authenticateUsing(function (Request $request): User {
            $lockout = app(LoginLockout::class);
            $email = (string) $request->input(Fortify::username());

            // 1. Akun terkunci?
            if ($lockout->isLocked($email)) {
                throw ValidationException::withMessages([
                    Fortify::username() => 'Akun terkunci sementara karena terlalu banyak percobaan gagal. Coba lagi dalam '.$lockout->minutesRemaining($email).' menit.',
                ]);
            }

            $user = User::query()->where('email', $email)->first();

            // 2. Kredensial & status — semua gagal pesan sama (anti-enumerasi akun).
            if ($user !== null
                && $user->isActive()
                && Hash::check((string) $request->input('password'), $user->password)) {
                $lockout->clear($email);

                return $user;
            }

            // 3. Catat kegagalan
            $lockout->recordFailure($email);

            throw ValidationException::withMessages([
                Fortify::username() => 'Surel atau kata sandi tidak sesuai.',
            ]);
        });

        RateLimiter::for('login', function (Request $request) {
            // Lapis per-IP terhadap brute force massal.
            // Penguncian per akun ditangani LoginLockout.
            return Limit::perMinute(20)->by($request->ip() ?? 'unknown');
        });
    }
}
