<?php

namespace Modules\Messagings\Listeners;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\MessageRead;

class LogMessageReadEventListener
{
    public function handle(MessageRead $event): void
    {
        $user = User::find($event->userId);

        activity()
            ->causedBy($user)
            ->performedOn($event->message)
            ->log('Message.Read');
    }
}
