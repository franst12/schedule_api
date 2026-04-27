<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jadwal_kuliah_id',
        'judul_catatan',
        'isi_catatan',
        'link_materi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class);
    }
}