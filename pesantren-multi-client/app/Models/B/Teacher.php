<?php

namespace App\Models\B;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id', 'nip', 'full_name', 'email', 'phone_number',
    ];

    protected $dates = ['deleted_at'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
