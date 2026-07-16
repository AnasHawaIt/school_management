<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Events\MessageReplied;

class LogMessageRepliedEventListener
{
    public function handle(MessageReplied $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->message)
            ->log('Message Replied');
    }
}
