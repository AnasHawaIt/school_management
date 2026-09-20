<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\Message\MessageCreated;
use Modules\Messagings\app\Listeners\Broadcasting\Events\MessageCreatedBroadcast;

class BroadcastMessageCreated
{
    public function handle(MessageCreated $event): void
    {
        broadcast(
            new MessageCreatedBroadcast(
                MessagePayload::make($event->message),
                $event->senderId,
            )
        );
    }
}
