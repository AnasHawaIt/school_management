<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Events\MessageCreated;

class LogMessageCreatedEventListener
{
    public function handle(MessageCreated $event): void
    {
        activity()
            ->causedBy($event->senderId)
            ->performedOn($event->message)
            ->log('Message.Created');
    }

}
