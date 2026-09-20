<?php

namespace Modules\Messagings\app\Listeners\Broadcasting;

use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Events\AttachmentDeleted;
use Modules\Messagings\app\Listeners\Broadcasting\Events\AttachmentDeletedBroadcast;

class BroadcastAttachmentDeleted
{
    public function handle(AttachmentDeleted $event): void
    {
        $message = Message::query()
            ->select([
                'id',
                'conversation_id',
            ])
            ->find($event->messageId);

        if (!$message) {
            return;
        }

        AttachmentDeletedBroadcast::dispatch(
            attachmentId: $event->attachmentId,
            messageId: $event->messageId,
            conversationId: $message->conversation_id,
            fileName: $event->fileName,
        );
    }
}
