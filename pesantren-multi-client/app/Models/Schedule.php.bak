<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenantTrait;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use BelongsToTenantTrait;

    protected $fillable = ['class_id', 'subject_id', 'teacher_id', 'day', 'start_time', 'end_time'];

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}