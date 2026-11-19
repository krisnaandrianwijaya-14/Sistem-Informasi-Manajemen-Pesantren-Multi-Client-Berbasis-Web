<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StudentCard extends Model
{
    protected $fillable = ['tenant_id', 'student_id', 'token', 'status'];

    public static function issue(Student $student): self
    {
        static::where('student_id', $student->id)->where('status', 'active')->update(['status' => 'blocked']);

        return static::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'token' => Str::random(40),
            'status' => 'active',
        ]);
    }
}