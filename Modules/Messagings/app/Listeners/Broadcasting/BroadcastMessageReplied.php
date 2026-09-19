<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\Message\MessageReplied;
use Modules\Messagings\app\Listeners\Broadcasting\Events\MessageRepliedBroadcast;

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
