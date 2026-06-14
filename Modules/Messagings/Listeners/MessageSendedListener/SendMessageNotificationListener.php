<?php

namespace Modules\Messagings\Listeners\MessageSendedListener;


use Modules\Messagings\Events\MessageSent;

class SendMessageNotificationListener
{
    public function handle(MessageSent $event): void
    {
        $message = $event->message;

        foreach (
            $message->recipients as $recipient
        ) {
            $recipient->recipient
                ->notify(
                    new NewMessageNotification(
                        $message
                    )
                );
        }
    }
}
