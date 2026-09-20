<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\Message\MessageDeleted;
use Modules\Messagings\app\Listeners\Broadcasting\Events\MessageDeletedBroadcast;

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
