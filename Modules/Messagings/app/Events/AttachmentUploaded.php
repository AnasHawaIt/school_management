<?php

namespace Modules\Messagings\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Entities\MessageAttachment;

class AttachmentUploaded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Message $message,
        public MessageAttachment $attachment
    ) {
    }
}
