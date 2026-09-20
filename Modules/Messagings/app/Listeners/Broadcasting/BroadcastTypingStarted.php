<?php


namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Events\Message\TypingStarted;
use Modules\Messagings\app\Listeners\Broadcasting\Events\TypingStartedBroadcast;

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
