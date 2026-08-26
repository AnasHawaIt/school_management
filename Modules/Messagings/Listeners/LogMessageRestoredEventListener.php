<?php

namespace Modules\Messagings\Listeners;


use Modules\Core\Entities\User;
use Modules\Messagings\Events\MessageRestored;

class LogMessageRestoredEventListener
{
    public function handle(MessageRestored $event): void
    {
        $user = User::find($event->userId);

        activity()
            ->causedBy($user)
            ->performedOn($event->message)
            ->log('Message.Restored');
    }
}
