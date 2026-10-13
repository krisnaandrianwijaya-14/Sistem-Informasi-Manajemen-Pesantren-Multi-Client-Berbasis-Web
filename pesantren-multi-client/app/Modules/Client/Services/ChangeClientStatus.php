<?php

namespace App\Modules\Client\Services;

use App\Models\Client;
use DomainException;
use Illuminate\Support\Facades\DB;

/**
 * UC-21 alur 3a — Aktif/nonaktifkan client.
 *
 * Client nonaktif tidak bisa diakses oleh admin client-nya (middleware akan
 * menolak) dan hilang dari katalog publik. Data historis tetap utuh — tidak
 * ada delete. Perubahan tercatat di audit log.
 */
final class ChangeClientStatus
{
    /** Status yang boleh dipilih dari UI. */
    public const ALLOWED = ['active', 'suspended'];

    public function __construct(private AuditLogger $audit) {}

    public function handle(Client $client, string $status): Client
    {
        if (! in_array($status, self::ALLOWED, true)) {
            throw new DomainException('Status client tidak dikenal.');
        }

        return DB::transaction(function () use ($client, $status): Client {
            $locked = Client::query()->lockForUpdate()->findOrFail($client->id);
            $before = $locked->status;

            if ($before === $status) {
                return $locked; // tidak ada perubahan
            }

            $locked->forceFill(['status' => $status])->save();

            $this->audit->record(
                modelType: 'client',
                modelId: $locked->id,
                action: $status === 'active' ? 'activated' : 'deactivated',
                before: ['status' => $before],
                after: ['status' => $status],
                clientId: $locked->id,
            );

            return $locked;
        });
    }
}
