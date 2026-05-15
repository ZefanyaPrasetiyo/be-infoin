<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


#[Fillable(['id_user', 'id_kategory', 'deskripsi', 'bukti_laporan', 'status', 'latitude', 'longitude', 'alamat', 'kepercayaan_ai', 'label_ai'])]
class Report extends Model
{
      protected $keyType = 'string';

    public $incrementing = false;
    use SoftDeletes;
    public function Comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_report');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
    public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'id_user');
}
}
