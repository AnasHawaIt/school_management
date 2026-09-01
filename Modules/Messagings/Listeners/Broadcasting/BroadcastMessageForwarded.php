<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\Message\MessageForwarded;
use Modules\Messagings\Listeners\Broadcasting\Events\MessageForwardedBroadcast;

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
