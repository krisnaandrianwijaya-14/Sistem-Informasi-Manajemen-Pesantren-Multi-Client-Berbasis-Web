<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use BelongsToTenantTrait, SoftDeletes;

    protected $fillable = [
        'nis', 'full_name', 'gender', 'status',
        'birth_place', 'birth_date', 'class_id',
        'dormitory_name', 'room_number',
        'father_name', 'mother_name', 'phone_number', 'address'
    ];

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}