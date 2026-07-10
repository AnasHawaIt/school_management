<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Events\MessageRead;

class LogMessageReadEventListener
{
    public function handle(MessageRead $event): void
    {
        activity()
            ->causedBy($event->readerId)
            ->performedOn($event->message)
            ->log('Message Read');
    }
}
