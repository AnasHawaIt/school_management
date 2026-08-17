<?php


namespace Modules\Activities\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\Models\ActivityAttachment;


class ActivityAttachmentDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ActivityAttachment $attachment,
        public ?int $causedBy = null
    ) {
    }
}
