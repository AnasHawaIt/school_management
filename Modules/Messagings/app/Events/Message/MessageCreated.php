<?php

namespace Modules\Messagings\app\Events\Message;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\app\Entities\Message;

class MessageCreated
{
    use Dispatchable, SerializesModels;

    public Message $message;
    public int $senderId;

    public function __construct(Message $message, int $senderId)
    {
        $this->message = $message;
        $this->senderId = $senderId;
    }
}
