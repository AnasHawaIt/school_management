<?php

namespace Modules\Messagings\app\Listeners\Log;


use Modules\Messagings\app\Events\Message\MessageDeleted;

class LogMessageDeletedEventListener
{
    public function handle(MessageDeleted $event): void
    {

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->message)
            ->log('Message.Deleted');
    }
}
