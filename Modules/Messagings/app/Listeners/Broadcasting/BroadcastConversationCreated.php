<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\ConversationCreated;
use Modules\Messagings\app\Listeners\Broadcasting\Events\ConversationCreatedBroadcast;

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
