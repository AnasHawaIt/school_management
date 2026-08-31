<?php

namespace Modules\Messagings\Listeners\Log;

use Modules\Messagings\Entities\Message;
use Modules\Messagings\Events\AttachmentDeleted;

class LogAttachmentDeletedEventListener
{
    public function handle(AttachmentDeleted $event): void
    {
        $message = Message::find($event->messageId);

        if (!$message) {
            return;
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($message)
            ->withProperties([
                'attachment_id' => $event->attachmentId,
                'file_name'     => $event->fileName,
                'file_path'     => $event->filePath,
            ])
            ->log('Attachment.Deleted');
    }
}
