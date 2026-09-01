<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\ConversationCreated;
use Modules\Messagings\Listeners\Broadcasting\Events\ConversationCreatedBroadcast;

class BroadcastConversationCreated
{
    public function handle(ConversationCreated $event): void
    {
        $conversation = $event->conversation;

        ConversationCreatedBroadcast::dispatch(
            conversationId: $conversation->id,
            type: $conversation->type,
            title: $conversation->title,
            createdBy: $conversation->created_by,
            createdAt: $conversation->created_at?->toISOString(),
        );
    }
}
