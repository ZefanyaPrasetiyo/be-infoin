<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


Fillable:(['id_user', 'id_conversation']);

class ConversationsMember extends Model
{
      protected $keyType = 'string';

    public $incrementing = false;
    use SoftDeletes;
    public function conversation(): HasMany
    {
        return $this->hasMany(Conversation::class, 'id_conversation');
    }
}
