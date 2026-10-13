<?php

namespace App\Modules\Client\Services;

use App\Models\Client;
use App\Models\ClientUser;
use App\Models\User;
use DomainException;

/**
 * Modul 5 — Penugasan role anggota client (owner/manager/finance/viewer).
 * Mencegah penghapusan owner terakhir client.
 */
final class AssignClientRole
{
    public function __construct(private AuditLogger $audit) {}

public function assign(Client $client, User $user, string $role): void
{
    ClientUser::updateOrCreate(
        [
            'client_id' => $client->id,
            'user_id' => $user->id,
            'role' => $role,
        ],
        [
            'is_active' => true,
        ],
    );

    $this->audit->record(
        modelType: 'client_user',
        modelId: $user->id,
        action: 'assigned',
        before: null,
        after: ['user_id' => $user->id, 'role' => $role],
        clientId: $client->id,
    );
}

public function remove(Client $client, User $user, string $role): void
{
    if ($role === 'owner') {
        $owners = ClientUser::query()
            ->where('client_id', $client->id)
            ->where('role', 'owner')
            ->where('is_active', true)
            ->count();

        if ($owners <= 1) {
            throw new DomainException('Tidak boleh menghapus owner terakhir client.');
        }
    }

    ClientUser::query()
        ->where('client_id', $client->id)
        ->where('user_id', $user->id)
        ->where('role', $role)
        ->delete();

    $this->audit->record(
        modelType: 'client_user',
        modelId: $user->id,
        action: 'removed',
        before: ['user_id' => $user->id, 'role' => $role],
        after: null,
        clientId: $client->id,
    );
}
}
