<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;


use Modules\Messagings\app\Events\Message\MessageRestored;
use Modules\Messagings\app\Listeners\Broadcasting\Events\MessageRestoredBroadcast;

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
