<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable; // Opsional di PHP 8.2+
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


#[Fillable(['id_laporan', 'status', 'id_laporan'])] 
class History extends Model
{
      protected $keyType = 'string';

    public $incrementing = false;
    use SoftDeletes;
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'id_laporan');
    }
}