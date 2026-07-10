<?php


namespace Modules\Messagings\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Messagings\app\Emails\MessageMail;
use Modules\Messagings\Events\MessageCreated;

class SendEmailListener implements ShouldQueue
{
    public function handle(MessageCreated $event): void
    {
        foreach ($event->message->recipients as $recipient) {
            if ($recipient->receive_email_copy) {
                Mail::to($recipient->email)
                    ->queue(new MessageMail($event->message));
            }
        }
    }
}
