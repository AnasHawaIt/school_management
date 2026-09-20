<?php


namespace Modules\Activities\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\app\Entities\ActivityAttachment;


class ActivityAttachmentUploaded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ActivityAttachment $attachment,
        public ?int $causedBy = null
    ) {
    }
}
