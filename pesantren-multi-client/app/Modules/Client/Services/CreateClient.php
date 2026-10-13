<?php

namespace App\Modules\Client\Services;

use App\Models\Client;
use App\Models\ClientAdminFee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Modules\Client\Services\AuditLogger;

/**
 * Modul 5 — Membuat client baru beserta fee administrasi awal,
 * akun owner (PIC), dan rekening bank awal secara atomik.
 *
 * Kredensial awal dikirim SETELAH commit berupa tautan atur-kata-sandi
 * (password broker), bukan kata sandi mentah.
 */
final class CreateClient
{
    public function __construct(
        private AuditLogger $audit,
        private ManageBankAccount $bankAccounts,
        private AssignClientRole $roles,
    ) {}

    /** @param array<string, mixed> $data */
    public function handle(array $data): Client
    {
        return DB::transaction(function () use ($data): Client {
            $client = (new Client)->forceFill([
                // 'code' => $data['code'],
                'slug' => $data['slug'],
                'name' => $data['name'],
                'status' => 'active',
            ]);
            $client->save();

            // Fee administrasi awal (effective-dated, valid_to = null)
            $fee = (new ClientAdminFee)->forceFill([
                'client_id' => $client->id,
                'fee_per_student' => (int) $data['fee_per_student'],
                'valid_from' => now(),
                'valid_to' => null,
            ]);
            $fee->save();

            $this->audit->record(
                modelType: 'client',
                modelId: $client->id,
                action: 'created',
                before: null,
                after: $client->only(['code', 'slug', 'name', 'status']),
                clientId: $client->id,
            );

            // Onboarding PIC sebagai owner
            if (isset($data['pic_email'])) {
                $owner = (new User)->forceFill([
                    'name' => $data['pic_name'],
                    'email' => $data['pic_email'],
                    'password' => Str::password(32),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
                $owner->save();

                $this->roles->assign($client, $owner, 'owner');

                DB::afterCommit(fn () => Password::broker()->sendResetLink([
                    'email' => $owner->email,
                ]));
            }

            // Rekening awal
            if (isset($data['account_number'])) {
                $this->bankAccounts->store($client, $data)
                    ->forceFill(['is_primary' => true])
                    ->save();
            }

            return $client;
        });
    }
}
