<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['user_id', 'course_id', 'day', 'start_time', 'end_time', 'room'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
