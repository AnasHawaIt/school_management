<?php

namespace Modules\Messagings\Listeners;


use Modules\Core\Entities\User;
use Modules\Messagings\Events\MessagesDeleted;

class LogMessageDeletedEventListener
{
    public function handle(MessagesDeleted $event): void
    {

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->Message)
            ->log('Message.Deleted');
    }
}
