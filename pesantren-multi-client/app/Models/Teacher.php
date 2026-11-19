<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use BelongsToClient;

    protected $table = 'teachers';

    protected $fillable = [
        'client_id', 'user_id', 'name',
        'employee_number', 'phone', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
}