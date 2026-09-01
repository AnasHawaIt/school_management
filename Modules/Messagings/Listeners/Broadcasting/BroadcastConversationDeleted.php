<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\ConversationDeleted;
use Modules\Messagings\Listeners\Broadcasting\Events\ConversationDeletedBroadcast;

class BroadcastConversationDeleted
{
    public function handle(ConversationDeleted $event): void
    {
        ConversationDeletedBroadcast::dispatch(
            conversationId: $event->conversation->id,
            deletedBy: $event->userId,
        );
    }
}
