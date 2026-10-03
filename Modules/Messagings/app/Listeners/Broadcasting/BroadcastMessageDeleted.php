<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\Message\MessageDeleted;
use Modules\Messagings\Listeners\Broadcasting\Events\MessageDeletedBroadcast;

class BroadcastMessageDeleted
{
    public function handle(MessageDeleted $event): void
    {
        broadcast(
            new MessageDeletedBroadcast(
                messageId: $event->message->id,
                conversationId: $event->message->conversation_id,
                deletedBy: $event->userId,
            )
        );
    }
}
