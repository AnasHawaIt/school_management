<?php

namespace Modules\Messagings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\Entities\Message;

class MessageForwarded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Message $originalMessage,
        public Message $forwardedMessage,
        public int $userId
    ) {}
}
