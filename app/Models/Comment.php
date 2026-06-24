<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


#[Fillable(['id_user', 'id_report', 'message', 'id_parent'])]
class Comment extends Model
{
      protected $keyType = 'string';

    public $incrementing = false;

    use SoftDeletes, HasUlids;
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'id_user');
    } 

    public function report(): BelongsTo
    {
        return $this->BelongsTo(Report::class, 'id_report');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_parent');
    }
    public function parent(): BelongsTo
    {
    return $this->BelongsTo(Comment::class, 'id_parent');
    }
}