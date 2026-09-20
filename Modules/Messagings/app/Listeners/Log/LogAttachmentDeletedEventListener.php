<?php

namespace Modules\Messagings\app\Listeners\Log;

use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Events\AttachmentDeleted;

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
