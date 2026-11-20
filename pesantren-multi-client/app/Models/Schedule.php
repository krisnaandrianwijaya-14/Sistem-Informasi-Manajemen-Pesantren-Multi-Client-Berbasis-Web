<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use BelongsToTenantTrait;

    protected $table = 'schedules';

    protected $fillable = [
        'client_id', 'tenant_id', 'class_id', 'subject_id', 'teacher_id',
        'day_of_week', 'start_time', 'end_time', 'room',
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