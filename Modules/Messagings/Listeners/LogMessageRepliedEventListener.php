<?php

namespace Modules\Messagings\Listeners;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\MessageReplied;

class LogMessageRepliedEventListener
{
    public function handle(MessageReplied $event): void
    {
        $user = User::find($event->userId);

        activity()
            ->causedBy($user)
            ->performedOn($event->reply)
            ->log('Message.Replied');
    }
}
