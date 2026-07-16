<?php

namespace Modules\Messagings\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\Entities\Message;

class AttachmentDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Message $message,
    ) {}
}
