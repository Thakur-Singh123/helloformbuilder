<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    public $timestamps = false;

    protected $table = 'chat_messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_role',
        'message',
        'image',
        'is_read',
        'mockup_status',
        'created_at',
    ];

    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function imageUrl(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        return url('chat-image/' . $this->image);
    }

    public function imageDownloadUrl(): ?string
    {
        $url = $this->imageUrl();

        return $url ? $url . '?download=1' : null;
    }
}
