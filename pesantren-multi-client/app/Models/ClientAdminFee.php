<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient; // buat jika belum ada (mirip BelongsToTenant)
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAdminFee extends Model
{
    use BelongsToClient;

    protected $fillable = ['fee_per_student', 'valid_from', 'valid_to'];

    protected function casts(): array
    {
        return [
            'fee_per_student' => 'integer',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
        ];
    }

    /** Interval setengah-terbuka [valid_from, valid_to): tanpa celah & tumpang tindih. */
    #[Scope]
    protected function effectiveAt(Builder $query, CarbonInterface $instant): void
    {
        $query->where('valid_from', '<=', $instant)
            ->where(function (Builder $q) use ($instant) {
                $q->whereNull('valid_to')->orWhere('valid_to', '>', $instant);
            });
    }

    /** @return BelongsTo<Client, $this> */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
