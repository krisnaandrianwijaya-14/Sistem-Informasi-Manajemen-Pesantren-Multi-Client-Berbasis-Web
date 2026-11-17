<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\ClientUser;
use App\Models\User;

/**
 * Modul 5 — Otorisasi client.
 *
 * Prinsip:
 * - Platform Admin (role: platform_admin) BUKAN member client manapun;
 *   dia admin global yang boleh kelola semua client.
 * - Admin Pesantren / pengurus (via client_users) hanya kelola client-nya.
 */
class ClientPolicy
{
    private function isPlatformAdmin(User $user): bool
    {
        return $user->role === 'platform_admin';
    }

    /** @param list<string> $roles */
    private function manages(User $user, int $clientId, array $roles): bool
    {
        return ClientUser::query()
            ->where('user_id', $user->id)
            ->where('client_id', $clientId)
            ->whereIn('role', $roles)
            ->exists();
    }

    public function viewAny(User $user): bool
    {
        return $this->isPlatformAdmin($user)
            || ClientUser::query()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $this->isPlatformAdmin($user)
            || ClientUser::query()
                ->where('user_id', $user->id)
                ->whereIn('role', ['owner', 'manager'])
                ->exists();
    }

    public function update(User $user, Client $client): bool
    {
        return $this->isPlatformAdmin($user)
            || $this->manages($user, $client->id, ['owner', 'manager', 'finance']);
    }

    public function scheduleCommission(User $user, Client $client): bool
    {
        return $this->update($user, $client);
    }

    public function manageBank(User $user, Client $client): bool
    {
        return $this->update($user, $client);
    }

    public function assignRole(User $user, Client $client): bool
    {
        return $this->isPlatformAdmin($user)
            || $this->manages($user, $client->id, ['owner', 'manager']);
    }
}
