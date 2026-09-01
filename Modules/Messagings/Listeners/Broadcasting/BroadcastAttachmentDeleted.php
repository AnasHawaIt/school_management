<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Events\AttachmentDeleted;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Listeners\Broadcasting\Events\AttachmentDeletedBroadcast;

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
