<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['id_report', 'id_location', 'latitude', 'longitude', 'alamat', 'kepercayaan_ai', 'label_ai'])]

class DetailReport extends Model
{
    use HasUlids;
    protected $keyType = 'string';
    public $incrementing = false;

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'id_report');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'id_location');
    }
}