<?php

namespace Modules\Messagings\app\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\app\Events\Message\MessageCreated;
use Modules\Notifications\app\Services\NotificationService;

class SendMessageNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(MessageCreated $event): void
    {
        $message = $event->message;

        $recipientIds = $message->recipients()
            ->pluck('recipient_id')
            ->toArray();

        if (empty($recipientIds)) {
            return;
        }

        $notificationBody = match ($message->type) {
            'voice' => '🎙️ New voice message',
            default => $message->subject
                ?: 'You have received a new message',
        };

        $this->notificationService->sendToUsers(
            userIds: $recipientIds,
            title: 'New Message',
            body: $notificationBody,
            type: 'message_created',
            data: [
                'entity' => 'message',
                'action' => 'CREATE',
                'message_id' => $message->id,
                'conversation_id' => $message->conversation_id,
                'sender_id' => $message->sender_id,
                'message_type' => $message->type,
            ]
        );
    }
}
