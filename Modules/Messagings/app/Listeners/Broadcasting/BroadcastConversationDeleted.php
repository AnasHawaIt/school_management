<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\ConversationDeleted;
use Modules\Messagings\app\Listeners\Broadcasting\Events\ConversationDeletedBroadcast;

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
