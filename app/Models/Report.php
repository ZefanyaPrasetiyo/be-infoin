<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['kode_report', 'id_user', 'id_kategori', 'deskripsi', 'judul_laporan', 'status', 'bukti_laporan', 'catatan'])]


class Report extends Model
{
    use HasUlids, SoftDeletes; 
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'bukti_laporan' => 'array',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(DetailReport::class, 'id_report');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

  
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }

}