<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\Message\MessageRead;
use Modules\Messagings\app\Listeners\Broadcasting\Events\MessageReadBroadcast;

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
