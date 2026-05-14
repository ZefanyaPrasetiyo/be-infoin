<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;



#[Fillable(['id_conversation', 'id_user', 'message'])]
class Message extends Model
{
    use SoftDeletes;    
    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class,"id_conversation");
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class,"id_user");
    }
}
