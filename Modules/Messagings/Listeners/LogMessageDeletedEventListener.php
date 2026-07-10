<?php

namespace Modules\Messagings\Listeners;


use Modules\Messagings\Events\MessagesDeleted;

class LogMessageDeletedEventListener
{
    public function handle(MessagesDeleted $event): void
    {
        activity()
            ->performedOn($event->Message)
            ->log('Message Deleted');
    }
}
