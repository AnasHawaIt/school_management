<?php

namespace Modules\Messagings\Listeners;



use Modules\Messagings\Events\MessageCreated;

class UpdateUnreadCounterListener
{
    public function handle(MessageCreated $event): void
    {
        foreach ($event->message->recipients as $recipient) {
            $recipient->increment('unread_messages');
        }
    }
}
