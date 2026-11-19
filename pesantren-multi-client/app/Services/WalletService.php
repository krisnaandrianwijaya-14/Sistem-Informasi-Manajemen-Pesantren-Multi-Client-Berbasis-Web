<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use DomainException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletService
{
    public const MIN_TOPUP = 10000;

    public function topUp(int $walletId, int $amount, string $key, ?int $by = null, ?string $ref = null): WalletTransaction
    {
        if ($amount < self::MIN_TOPUP) {
            throw new InvalidArgumentException('Nominal top-up minimal Rp 10.000.');
        }

        return $this->apply($walletId, 'topup', $amount, $key, $by, $ref);
    }

    public function debit(int $walletId, int $amount, string $key, ?int $by = null, ?string $ref = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal harus lebih dari 0.');
        }

        return $this->apply($walletId, 'debit', $amount, $key, $by, $ref);
    }

    private function apply(int $walletId, string $type, int $amount, string $key, ?int $by, ?string $ref): WalletTransaction
    {
        try {
            return DB::transaction(function () use ($walletId, $type, $amount, $key, $by, $ref) {
                $wallet = Wallet::lockForUpdate()->findOrFail($walletId);

                // Retry dengan key yang sama tidak boleh memotong dua kali.
                if ($existing = WalletTransaction::where('idempotency_key', $key)->first()) {
                    return $existing;
                }

                if ($type === 'debit' && $wallet->balance < $amount) {
                    throw new DomainException('Saldo tidak mencukupi.');
                }

                $wallet->balance += $type === 'topup' ? $amount : -$amount;
                $wallet->save();

                return WalletTransaction::create([
                    'tenant_id'       => $wallet->tenant_id,
                    'wallet_id'       => $wallet->id,
                    'type'            => $type,
                    'status'          => 'success',
                    'amount'          => $amount,
                    'balance_after'   => $wallet->balance,
                    'idempotency_key' => $key,
                    'reference'       => $ref,
                    'created_by'      => $by,
                ]);
            });
        } catch (UniqueConstraintViolationException) {
            // Dua request kembar bersamaan: yang kalah ambil hasil milik yang menang.
            return WalletTransaction::where('idempotency_key', $key)->firstOrFail();
        }
    }
}