<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


#[Fillable(['nama'])]
class Category extends Model
{
    use SoftDeletes;
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'id_kategori');
    }   
}
