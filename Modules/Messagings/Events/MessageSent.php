<?php

namespace Modules\Messagings\Events;
namespace Modules\Messagings\Events;

use Modules\Messagings\Entities\Message;

class MessageSent
{
    public function __construct(
        public Message $message,
        public int $userId,
        public ?string $socketId = null
    ) {}
}
