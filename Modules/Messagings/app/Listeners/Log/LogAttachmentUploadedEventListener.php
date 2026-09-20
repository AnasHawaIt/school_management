<?php

namespace Modules\Messagings\app\Listeners\Log;

use Modules\Messagings\app\Events\AttachmentUploaded;

class  LogAttachmentUploadedEventListener
{
    public function handle(AttachmentUploaded $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attachment)
            ->log('Attachment.Uploaded');
    }

}
