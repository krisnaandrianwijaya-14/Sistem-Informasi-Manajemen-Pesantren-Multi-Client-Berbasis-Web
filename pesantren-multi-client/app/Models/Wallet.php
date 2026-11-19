<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['tenant_id', 'student_id', 'balance'];

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }
}