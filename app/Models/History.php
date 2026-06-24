<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Report;

class History extends Model
{
    use SoftDeletes; 

    protected $table = 'history_report';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_user',
        'id_laporan',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "id_user");
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, "id_laporan");
    }
}