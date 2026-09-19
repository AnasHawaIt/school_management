<?php


namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\Message\TypingStopped;
use Modules\Messagings\app\Listeners\Broadcasting\Events\TypingStoppedBroadcast;

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
