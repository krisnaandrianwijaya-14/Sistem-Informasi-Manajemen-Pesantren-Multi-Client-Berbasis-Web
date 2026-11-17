<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientAdminFee;
use App\Models\ClientUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Modul 5 — Isi fee administrasi awal + owner untuk client existing.
 */
class ClientFeeSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::query()->get();

        foreach ($clients as $client) {
            // 1. Fee administrasi awal (kalau belum ada)
            $hasFee = ClientAdminFee::query()
                ->withoutGlobalScope('client')
                ->where('client_id', $client->id)
                ->whereNull('valid_to')
                ->exists();

            if (! $hasFee) {
                ClientAdminFee::query()->create([
                    'client_id' => $client->id,
                    'fee_per_student' => 5000,
                    'valid_from' => now()->startOfYear(),
                    'valid_to' => null,
                ]);

                $this->command->info("Fee untuk {$client->name} dibuat.");
            }

            // 2. Owner
            $hasOwner = ClientUser::query()
                ->where('client_id', $client->id)
                ->where('role', 'owner')
                ->exists();

            if (! $hasOwner) {
                $email = 'admin@'.$client->slug.'.test';

                $owner = User::query()->firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => 'Admin '.$client->name,
                        'password' => Hash::make('password'),
                        'role' => 'admin_pesantren',
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ],
                );

                ClientUser::query()->create([
                    'client_id' => $client->id,
                    'user_id' => $owner->id,
                    'role' => 'owner',
                    'is_active' => true,
                ]);

                $this->command->info("Owner {$owner->email} ditetapkan untuk {$client->name}.");
            }
        }
    }
}
