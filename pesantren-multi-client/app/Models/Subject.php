<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use BelongsToTenantTrait;

    protected $fillable = ['name'];
}