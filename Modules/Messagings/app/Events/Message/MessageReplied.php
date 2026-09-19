<?php

namespace Modules\Messagings\app\Events\Message;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\app\Entities\Message;

class MessageReplied
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Message $originalMessage,
        public Message $reply,
        public int $userId
    ) {}
}
