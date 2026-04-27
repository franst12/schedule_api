<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $fillable = [
        'user_id',
        'nama_matkul',
        'kode_matkul',
        'sks'
    ];

    public function jadwals()
    {
        return $this->hasMany(JadwalKuliah::class);
    }
}
