<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use BelongsToTenantTrait;

    protected $fillable = [
        'class_id', 'subject_id', 'teacher_id',
        'day', 'start_time', 'end_time'
    ];

    public function classRoom()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function academicAttendances()
    {
        return $this->hasMany(AcademicAttendance::class, 'schedule_id');
    }
}