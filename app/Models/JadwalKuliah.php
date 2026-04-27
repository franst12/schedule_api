<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    protected $fillable = ['user_id', 'mata_kuliah_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
