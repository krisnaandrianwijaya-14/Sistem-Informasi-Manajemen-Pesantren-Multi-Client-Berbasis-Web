<?php

namespace Tests\Feature;

use App\Models\Wallet;
use App\Services\WalletService;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    private function wallet(int $balance = 0): Wallet
    {
        return Wallet::create(['tenant_id' => 1, 'student_id' => 1, 'balance' => $balance]);;
    }

    public function test_saldo_tidak_boleh_minus(): void
    {
        $wallet = $this->wallet(5000);

        $this->expectException(DomainException::class);
        app(WalletService::class)->debit($wallet->id, 6000, 'k-1');
    }

    public function test_retry_dengan_key_sama_tidak_memotong_dua_kali(): void
    {
        $wallet = $this->wallet(20000);
        $svc = app(WalletService::class);

        $svc->debit($wallet->id, 7000, 'k-2');
        $svc->debit($wallet->id, 7000, 'k-2');

        $this->assertSame(13000, $wallet->fresh()->balance);
        $this->assertDatabaseCount('wallet_transactions', 1);
    }

    public function test_topup_menambah_saldo_dan_tercatat(): void
    {
        $wallet = $this->wallet(0);

        app(WalletService::class)->topUp($wallet->id, 50000, 'k-3');

        $this->assertSame(50000, $wallet->fresh()->balance);
        $this->assertDatabaseHas('wallet_transactions', ['type' => 'topup', 'balance_after' => 50000]);
    }
}