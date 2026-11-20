<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use BelongsToTenantTrait, BelongsToClient, SoftDeletes;

    protected $fillable = [
        'client_id', 'tenant_id', 'nis', 'full_name', 'gender', 'status',
        'qr_token', 'birth_place', 'birth_date', 'class_id',
        'dormitory_name', 'room_number',
        'father_name', 'mother_name', 'phone_number', 'address'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    public function classRoom()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicAttendances()
    {
        return $this->hasMany(AcademicAttendance::class, 'student_id');
    }
}