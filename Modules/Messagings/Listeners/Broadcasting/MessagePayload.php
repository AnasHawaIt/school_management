<?php

namespace Modules\Messagings\Listeners\Broadcasting;

use Modules\Messagings\Entities\Message;

final class MessagePayload
{
    public static function make(Message $message): array
    {
        $message->loadMissing([
            'sender',
            'attachments',
        ]);

        return [
            'id' => $message->id,

            'conversation_id' => $message->conversation_id,

            'sender' => [
                'id' => $message->sender?->id,
                'name' => $message->sender?->name,
            ],

            'subject' => $message->subject,
            'body' => $message->body,

            'type' => $message->type,
            'priority' => $message->priority,

            'created_at' => $message->created_at?->toISOString(),
            'updated_at' => $message->updated_at?->toISOString(),

            'attachments' => $message->attachments
                ->map(fn ($attachment) => [
                    'id' => $attachment->id,
                    'file_name' => $attachment->file_name,
                    'mime_type' => $attachment->mime_type,
                    'file_size' => $attachment->file_size,
                    'duration' => $attachment->duration,
                    'url' => $attachment->file_url,
                ])
                ->values()
                ->all(),
        ];
    }
}
