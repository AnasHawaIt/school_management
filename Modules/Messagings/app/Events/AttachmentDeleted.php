<?php

namespace Modules\Messagings\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachmentDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $attachmentId,
        public int $messageId,
        public string $fileName,
        public string $filePath
    ) {}
}
