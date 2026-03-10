<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'response'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
