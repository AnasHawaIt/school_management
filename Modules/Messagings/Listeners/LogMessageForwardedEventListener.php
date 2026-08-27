<?php

namespace Modules\Messagings\Listeners;

use Modules\Core\Entities\User;
use Modules\Messagings\Events\MessageForwarded;

class LogMessageForwardedEventListener
{
    public function handle(MessageForwarded $event): void
    {
        $user = User::find($event->userId);

        activity()
            ->causedBy($user)
            ->performedOn($event->forwardedMessage)
            ->log('Message.Forwarded');
    }
}
