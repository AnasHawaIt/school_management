<?php

namespace Modules\Messagings\app\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\app\Events\Message\MessageFailed;

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
