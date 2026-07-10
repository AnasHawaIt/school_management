<?php

namespace Modules\Messagings\Listeners;



use Modules\Messagings\Events\MessageCreated;

class UpdateUnreadCounter
{
    public function handle(MessageCreated $event): void
    {
        foreach ($event->message->recipients as $recipient) {
            $recipient->increment('unread_messages');
        }
    }
}
