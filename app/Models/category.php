<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


#[Fillable(['nama'])]
class Category extends Model
{
      protected $keyType = 'string';

    public $incrementing = false;
    use SoftDeletes;
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'id_kategori');
    }   
}
