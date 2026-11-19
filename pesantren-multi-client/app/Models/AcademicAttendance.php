<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;

class AcademicAttendance extends Model
{
    use BelongsToTenantTrait;

    protected $fillable = ['schedule_id', 'student_id', 'date', 'status', 'note'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}