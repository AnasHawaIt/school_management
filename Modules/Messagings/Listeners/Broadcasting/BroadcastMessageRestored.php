<?php

namespace Modules\Messagings\Listeners\Broadcasting;


use Modules\Messagings\Events\Message\MessageRestored;
use Modules\Messagings\Listeners\Broadcasting\Events\MessageRestoredBroadcast;

class BroadcastMessageRestored
{
    public function handle(MessageRestored $event): void
    {
        MessageRestoredBroadcast::dispatch(
            $event->message->id,
            $event->message->conversation_id,
            $event->userId,
        );
    }
}
