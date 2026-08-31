<?php

namespace Modules\Messagings\Listeners\Log;


use Modules\Messagings\Events\Message\MessageDeleted;

class LogMessageDeletedEventListener
{
    public function handle(MessageDeleted $event): void
    {

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->Message)
            ->log('Message.Deleted');
    }
}
