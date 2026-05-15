<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


#[Fillable(['id_user', 'nama_panjang', 'alamat', 'foto_profil', 'tanggal_lahir', 'jenis_kelamin'])]
class Profile extends Model
{
      protected $keyType = 'string';

    public $incrementing = false;
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
