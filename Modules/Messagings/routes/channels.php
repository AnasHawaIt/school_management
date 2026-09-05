<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\Messagings\Entities\Conversation;

Broadcast::channel(
    'conversation.{conversationId}',
    function ($user, $conversationId) {

        $conversation = Conversation::find($conversationId);

        if (! $conversation) {
            return false;
        }

        return $conversation
            ->participants()
            ->where('users.id', $user->id)
            ->exists();
    }
);

Broadcast::channel(
    'conversation.{conversationId}',
    function ($user, $conversationId) {

        $conversation = Conversation::find($conversationId);

        if (! $conversation) {
            return false;
        }

        $isParticipant = $conversation
            ->participants()
            ->where('user_id', $user->id)
            ->exists();

        if (! $isParticipant) {
            return false;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
);
