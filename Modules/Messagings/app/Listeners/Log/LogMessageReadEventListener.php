<?php

namespace Modules\Messagings\Listeners\Log;

use Modules\Messagings\Events\Message\MessageRead;

class LogMessageReadEventListener
{
    public function handle(MessageRead $event): void
    {

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->message)
            ->log('Message.Read');
    }
}
