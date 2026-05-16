<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Tymon\JWTAuth\Contracts\JWTSubject; 

#[Fillable(['nama_panjang', 'email', 'password', 'nomor_telepon', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, HasUlids;
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Ambil identifier yang akan disimpan di klaim sub JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Ini akan mengambil ID user kamu
    }

    /**
     * Tambahkan data custom ke dalam payload JWT (opsional).
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }
}