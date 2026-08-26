<?php

namespace Modules\Messagings\Listeners;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\MessageFailed;

class LogMessageFailedEventListener
{
    public function handle(MessageFailed $event): void
    {
        $user = User::find($event->userId);

        activity()
            ->causedBy($user) 
            ->performedOn($event->message)
            ->log('Message.Failed');
    }

}
