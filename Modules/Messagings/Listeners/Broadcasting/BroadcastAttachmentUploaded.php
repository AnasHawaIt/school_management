<?php


namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\AttachmentUploaded;
use Modules\Messagings\Listeners\Broadcasting\Events\AttachmentUploadedBroadcast;

class BroadcastAttachmentUploaded
{
    public function handle(AttachmentUploaded $event): void
    {
        $attachment = $event->attachment;

        AttachmentUploadedBroadcast::dispatch(
            messageId: $event->message->id,
            conversationId: $event->message->conversation_id,
            attachment: [
                'id' => $attachment->id,
                'file_name' => $attachment->file_name,
                'mime_type' => $attachment->mime_type,
                'file_size' => $attachment->file_size,
                'duration' => $attachment->duration,
                'url' => $attachment->file_url,
            ],
        );
    }
}
