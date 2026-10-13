<?php

namespace App\Modules\Client\Services;

use App\Models\Client;
use App\Models\ClientAdminFee;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Support\Facades\DB;

/**
 * Modul 5 — Menjadwalkan versi fee administrasi baru (effective-dated).
 * Versi lama ditutup TEPAT pada waktu efektif versi baru — interval
 * setengah-terbuka [valid_from, valid_to) tanpa celah/tumpang tindih.
 */
final class ChangeAdminFeeSchedule
{
    public function __construct(private AuditLogger $audit) {}

    public function handle(
        Client $client,
        int $feePerStudent,
        CarbonInterface $effectiveAt,
    ): ClientAdminFee {
        return DB::transaction(function () use ($client, $feePerStudent, $effectiveAt): ClientAdminFee {
            $active = ClientAdminFee::query()
                ->withoutGlobalScope('client')
                ->where('client_id', $client->id)
                ->whereNull('valid_to')
                ->lockForUpdate()
                ->first();

            if ($active !== null && $active->valid_from >= $effectiveAt) {
                throw new DomainException(
                    'Waktu efektif harus setelah versi aktif (mencegah overlap).'
                );
            }

            $before = $active?->only(['id', 'fee_per_student', 'valid_from', 'valid_to']);

            $active?->forceFill(['valid_to' => $effectiveAt])->save();

            $new = (new ClientAdminFee)->forceFill([
                'client_id' => $client->id,
                'fee_per_student' => $feePerStudent,
                'valid_from' => $effectiveAt,
                'valid_to' => null,
            ]);
            $new->save();

            $this->audit->record(
                modelType: 'client_admin_fee',
                modelId: $new->id,
                action: 'scheduled',
                before: $before,
                after: $new->only(['fee_per_student', 'valid_from']),
                clientId: $client->id,
            );

            return $new;
        });
    }
}
