<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; // Import ini

class User extends Authenticatable implements JWTSubject // Tambahkan implements
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nim',     // Tambahkan NIM untuk profil mahasiswa
        'email',
        'password',
        'foto',    // Tambahkan field foto
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tambahkan dua method wajib dari JWTSubject ini di bawah:
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}