<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use BelongsToClient;

    protected $table = 'subjects';

    protected $fillable = ['client_id', 'name', 'code'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'subject_id');
    }
}