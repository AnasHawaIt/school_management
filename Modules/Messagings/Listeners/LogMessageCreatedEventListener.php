<?php

namespace Modules\Messagings\Listeners;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\MessageCreated;

class LogMessageCreatedEventListener
{

    public function handle(MessageCreated $event): void
    {
        $user = User::find($event->senderId);

        activity()
            ->causedBy($user)
            ->performedOn($event->message)
            ->log('Message.Created');
    }
}
