<?php

namespace Modules\Messagings\app\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\Entities\Message;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('New Message')
            ->view('messagings::emails.message')
            ->with([
                'messageData' => $this->message,
            ]);
    }
}
