<?php

namespace Modules\Messagings\app\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Events\Message\MessageCreated;

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
