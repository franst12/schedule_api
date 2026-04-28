<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Saya perbaiki 'descripotion' jadi 'description' ya ngab
    protected $fillable = [
        'user_id',
        'course_id',
        'task_title',
        'description',
        'deadline',
        'is_finished'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}