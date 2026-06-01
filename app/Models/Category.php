<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Category extends Model
{
    use SoftDeletes, HasUlids;

    protected $fillable = [
        'nama',
        'kode_kategori',
        'slug'
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'id_kategori');
    }
}