<?php
use Illuminate\Support\Facades\Broadcast;
use Modules\Messagings\Entities\Conversation;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {

    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    return $conversation
        ->participants()
        ->where('user_id', $user->id)
        ->exists();
});
