<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['id_user', 'nama_panjang', 'alamat', 'foto_profil', 'tanggal_lahir', 'jenis_kelamin'])]
class Profile extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
