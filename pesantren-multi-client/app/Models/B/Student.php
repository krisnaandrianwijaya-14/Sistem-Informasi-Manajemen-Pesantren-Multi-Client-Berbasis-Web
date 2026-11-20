<?php

namespace App\Models\B;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id', 'client_id', 'nis', 'full_name', 'gender', 'status',
        'qr_token', 'birth_place', 'birth_date', 'class_id',
        'dormitory_name', 'room_number', 'father_name', 'mother_name',
        'phone_number', 'address',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicAttendances()
    {
        return $this->hasMany(AcademicAttendance::class);
    }
}
