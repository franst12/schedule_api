<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['user_id', 'mata_kuliah_id', 'judul_tugas', 'deskripsi', 'deadline', 'is_finished'];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
