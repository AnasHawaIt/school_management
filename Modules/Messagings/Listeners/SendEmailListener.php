<?php


namespace Modules\Messagings\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Messagings\app\Emails\MessageMail;
use Modules\Messagings\Events\Message\MessageCreated;

class SendEmailListener implements ShouldQueue
{
    public function handle(MessageCreated $event): void
    {
        foreach ($event->message->recipients as $messageRecipient) {

            $user = $messageRecipient->recipient;

            if (!$user) {
                continue;
            }

            if (
                $user->receive_email_copy &&
                !empty($user->email)
            ) {
                Mail::to($user->email)
                    ->queue(
                        new MessageMail($event->message)
                    );
            }
        }
    }
}
