<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Support\Auth\LoginLockout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * UC-19 Autentikasi Pengguna.
 * - Pesan generik untuk semua kegagalan (anti-enumerasi akun)
 * - Penguncian 5× gagal / 10 menit → 15 menit
 * - Akun nonaktif ditolak
 */
class LoginLockoutTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'email' => 'user@test.local',
            'password' => Hash::make('password'),
            'role' => 'platform_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ], $attrs));
    }

    private function attempt(string $email, string $password)
    {
        return $this->post(route('login.store'), [
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function test_wrong_password_and_unknown_email_get_same_generic_message(): void
    {
        $user = $this->makeUser();

        $this->attempt($user->email, 'salah')
            ->assertSessionHasErrors(['email' => 'Surel atau kata sandi tidak sesuai.']);

        $this->attempt('tidak-ada@test.local', 'apa-saja')
            ->assertSessionHasErrors(['email' => 'Surel atau kata sandi tidak sesuai.']);

        $this->assertGuest();
    }

    public function test_inactive_account_rejected_with_generic_message(): void
    {
        $user = $this->makeUser(['status' => 'suspended']);

        $this->attempt($user->email, 'password')
            ->assertSessionHasErrors(['email' => 'Surel atau kata sandi tidak sesuai.']);

        $this->assertGuest();
    }

    public function test_five_failures_lock_account_even_with_correct_password(): void
    {
        $user = $this->makeUser();

        foreach (range(1, LoginLockout::MAX_FAILURES) as $ignored) {
            $this->attempt($user->email, 'salah');
        }

        // Percobaan ke-6 dengan sandi BENAR tetap ditolak
        $this->attempt($user->email, 'password')
            ->assertSessionHasErrors('email');

        $this->assertStringContainsString(
            'Akun terkunci sementara',
            session('errors')->first('email'),
        );

        $this->assertGuest();
    }

    public function test_lock_expires_after_fifteen_minutes(): void
    {
        $user = $this->makeUser();

        foreach (range(1, LoginLockout::MAX_FAILURES) as $ignored) {
            $this->attempt($user->email, 'salah');
        }

        $this->travel(14)->minutes();
        $this->attempt($user->email, 'password')->assertSessionHasErrors('email');

        $this->travel(2)->minutes();
        $this->attempt($user->email, 'password')->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
    }

    public function test_successful_login_resets_failure_counter(): void
    {
        $user = $this->makeUser();

        foreach (range(1, LoginLockout::MAX_FAILURES - 1) as $ignored) {
            $this->attempt($user->email, 'salah');
        }

        // Login sukses sebelum mencapai batas
        $this->attempt($user->email, 'password')->assertSessionHasNoErrors();
        $this->post(route('logout'));

        // 4× gagal lagi tidak mengunci (counter sudah di-reset)
        foreach (range(1, 4) as $ignored) {
            $this->attempt($user->email, 'salah');
        }

        $this->attempt($user->email, 'password')->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
    }
}
