<?php

namespace App\Modules\Client\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Modul 5 — Jejak audit append-only untuk aksi administratif.
 * Field sensitif (nomor rekening, password) dibuang sebelum disimpan.
 */
final class AuditLogger
{
    /**
     * @param array<string, mixed>|null $before
     * @param array<string, mixed>|null $after
     */
    public function record(
        string $modelType,
        ?int $modelId,
        string $action,
        ?array $before = null,
        ?array $after = null,
        ?int $clientId = null,
    ): AuditLog {
        return AuditLog::create([
            'client_id' => $clientId,
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'changes' => [
                'before' => $before !== null ? $this->sanitize($before) : null,
                'after' => $after !== null ? $this->sanitize($after) : null,
            ],
            'ip_address' => Request::ip(),
            'user_agent' => (string) Request::userAgent(),
        ]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function sanitize(array $data): array
    {
        foreach ([
            'account_number',
            'account_number_cipher',
            'password',
            'password_hash',
            'remember_token',
        ] as $key) {
            unset($data[$key]);
        }

        return $data;
    }
}
