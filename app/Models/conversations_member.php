<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


Fillable:(['id_user', 'id_conversation']);

class ConversationsMember extends Model
{
    use SoftDeletes;
    public function conversation(): HasMany
    {
        return $this->hasMany(Conversation::class, 'id_conversation');
    }
}
