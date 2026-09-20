<?php

namespace Modules\Messagings\app\Listeners\Log;

use Modules\Messagings\app\Events\Message\MessageRead;

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
