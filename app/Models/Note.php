<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'note_title',
        'note_description',
        'note_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}