<?php


namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\Message\TypingStopped;
use Modules\Messagings\Listeners\Broadcasting\Events\TypingStoppedBroadcast;

class BroadcastTypingStopped
{
    public function handle(TypingStopped $event): void
    {
        TypingStoppedBroadcast::dispatch(
            $event->conversationId,
            $event->userId,
        );
    }
}
