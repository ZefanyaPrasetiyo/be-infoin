<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


#[Fillable(['id_user', 'id_komentar'])]
class CommentHistory extends Model
{
      protected $keyType = 'string';

    public $incrementing = false;
    /**
     * User yang melakukan aksi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Komentar yang memiliki riwayat
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'id_komentar');
    }
}