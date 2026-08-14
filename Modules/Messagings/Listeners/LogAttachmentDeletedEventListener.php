<?php

namespace Modules\Messagings\Listeners;

use Modules\Messagings\Events\AttachmentDeleted;

class LogAttachmentDeletedEventListener
{
    public function handle(AttachmentDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->image)
            ->log('Attachment.Deleted');
    }

}
