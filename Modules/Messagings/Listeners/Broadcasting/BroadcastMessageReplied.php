<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\Message\MessageReplied;
use Modules\Messagings\Listeners\Broadcasting\Events\MessageRepliedBroadcast;

class BroadcastMessageReplied
{
    public function handle(MessageReplied $event): void
    {
        MessageRepliedBroadcast::dispatch(
            $event->reply,
            $event->userId,
        );
    }
}
