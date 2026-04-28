<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;

class Course extends Model
{
    protected $fillable = [
        'user_id',
        'course_name',
        'course_code',
        'sks',
        'credits',
        'lecturer_name',
        'room',
        'day_of_week',
        'start_time',
        'end_time',
        'color_hex',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
