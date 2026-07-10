<?php

namespace Modules\Messagings\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\Events\MessageCreated;

class SendMessageNotificationListener implements ShouldQueue
{
    public function handle(MessageCreated $event): void
    {
        foreach ($event->message->recipients as $recipient) {
            $recipient->notify(new NewMessageNotification($event->message));
        }
    }
}
