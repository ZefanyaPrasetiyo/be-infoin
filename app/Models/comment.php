<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


#[Fillable(['id_user', 'id_report', 'message', 'id_parent'])]
class Comment extends Model
{
    /**
     * Relasi ke User yang menulis komentar
     */
    use SoftDeletes;
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    } 

    /**
     * Relasi ke Report yang dikomentari
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'id_report');
    }

    /**
     * Relasi untuk Balasan Komentar (Reply/Parent)
     * id_parent merujuk ke id di tabel yang sama (comments)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_parent');
    }
}
