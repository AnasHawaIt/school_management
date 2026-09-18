<?php

namespace Modules\Messagings\Listeners\Log;

use Modules\Core\app\Entities\User;
use Modules\Messagings\Events\Message\MessageForwarded;

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
