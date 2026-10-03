<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\Message\MessageRead;
use Modules\Messagings\Listeners\Broadcasting\Events\MessageReadBroadcast;

class BroadcastMessageRead
{
    public function handle(MessageRead $event): void
    {
        MessageReadBroadcast::dispatch(
            $event->message->id,
            $event->message->conversation_id,
            $event->userId,
        );
    }
}
