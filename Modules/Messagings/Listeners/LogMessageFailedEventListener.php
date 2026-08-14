<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Events\MessageFailed;

class LogMessageFailedEventListener
{
    public function handle(MessageFailed $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->message)
            ->log('Message.Failed');
    }

}
