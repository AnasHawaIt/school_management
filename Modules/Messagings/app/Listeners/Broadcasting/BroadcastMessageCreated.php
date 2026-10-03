<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\Message\MessageCreated;
use Modules\Messagings\Listeners\Broadcasting\Events\MessageCreatedBroadcast;

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
