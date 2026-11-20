<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientBankAccount extends Model
{
    protected $fillable = ['bank_code', 'account_holder', 'account_last4', 'status', 'is_primary'];

    protected function casts(): array
    {
        return [
            'account_number_cipher' => 'encrypted', // encrypted at rest
            'is_primary' => 'boolean',
        ];
    }

    /** @return BelongsTo<Client, $this> */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }
}
