<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Events\MessageForwarded;

class LogMessageForwardedEventListener
{
    public function handle(MessageForwarded $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->message)
            ->log('Message.Forwarded');
    }
}
