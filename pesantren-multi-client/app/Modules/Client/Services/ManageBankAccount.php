<?php

namespace App\Modules\Client\Services;

use App\Models\Client;
use App\Models\ClientBankAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Modul 5 — Kelola rekening bank client (pesantren) untuk penerimaan SPP.
 * Nomor disimpan terenkripsi + last4 untuk tampilan; audit disanitasi.
 */
final class ManageBankAccount
{
    public function __construct(private AuditLogger $audit) {}

    /** @param array<string, mixed> $data */
    public function store(Client $client, array $data): ClientBankAccount
    {
        return DB::transaction(function () use ($client, $data): ClientBankAccount {
            $account = (new ClientBankAccount)->forceFill([
                'client_id' => $client->id,
                'bank_code' => $data['bank_code'],
                'account_holder' => $data['account_holder'],
                'account_last4' => Str::substr((string) $data['account_number'], -4),
                'account_number_cipher' => (string) $data['account_number'],
                'status' => 'unverified',
                'is_primary' => false,
            ]);
            $account->save();

            $this->audit->record(
                modelType: 'client_bank_account',
                modelId: $account->id,
                action: 'created',
                before: null,
                after: [
                    'bank_code' => $data['bank_code'],
                    'account_last4' => $account->account_last4,
                    'account_number' => $data['account_number'], // akan disanitasi
                ],
                clientId: $client->id,
            );

            return $account;
        });
    }

    public function verify(ClientBankAccount $account, bool $approve): void
    {
        $account->forceFill([
            'status' => $approve ? 'verified' : 'rejected',
        ])->save();

        $this->audit->record(
            modelType: 'client_bank_account',
            modelId: $account->id,
            action: $approve ? 'verified' : 'rejected',
            before: null,
            after: ['status' => $account->status],
            clientId: $account->client_id,
        );
    }

    public function makePrimary(ClientBankAccount $account): void
    {
        DB::transaction(function () use ($account): void {
            ClientBankAccount::query()
                ->where('client_id', $account->client_id)
                ->whereKeyNot($account->id)
                ->update(['is_primary' => false]);

            $account->forceFill(['is_primary' => true])->save();
        });
    }
}
