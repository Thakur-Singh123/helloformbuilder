<?php

use App\Models\ChatConversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = ChatConversation::find($conversationId);
    if (!$conversation) {
        return false;
    }

    return $user->isAdmin() || (int) $conversation->user_id === (int) $user->id;
});

Broadcast::channel('chat.admin', function ($user) {
    return $user->isAdmin();
});
