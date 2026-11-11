<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use BelongsToTenantTrait;

    protected $fillable = ['name'];
}