<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\Message\MessageForwarded;
use Modules\Messagings\app\Listeners\Broadcasting\Events\MessageForwardedBroadcast;

class BroadcastMessageForwarded
{
    public function handle(MessageForwarded $event): void
    {
        MessageForwardedBroadcast::dispatch(
            $event->forwardedMessage,
            $event->userId,
        );
    }
}
