<?php


namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\Message\TypingStarted;
use Modules\Messagings\Listeners\Broadcasting\Events\TypingStartedBroadcast;

class BroadcastTypingStarted
{
    public function handle(TypingStarted $event): void
    {
        TypingStartedBroadcast::dispatch(
            $event->conversationId,
            $event->userId,
        );
    }
}
