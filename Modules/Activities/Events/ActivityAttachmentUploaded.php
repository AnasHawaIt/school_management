<?php


namespace Modules\Activities\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\Entities\ActivitySupervisor;
use Modules\Activities\Models\ActivityAttachment;


class ActivityAttachmentUploaded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ActivityAttachment $attachment
    ) {
    }
}
