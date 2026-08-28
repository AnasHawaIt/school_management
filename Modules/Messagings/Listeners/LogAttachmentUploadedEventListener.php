<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Events\AttachmentUploaded;

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
