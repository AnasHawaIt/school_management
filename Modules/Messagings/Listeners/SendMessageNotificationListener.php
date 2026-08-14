<?php

namespace Modules\Messagings\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\Events\MessageCreated;
use Modules\Notifications\Services\NotificationService;

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

        $this->notificationService->sendToUsers(
            userIds: $recipientIds,
            title: 'New Message',
            body: $message->subject,
            type: 'message_created',
            data: [
                'entity' => 'message',
                'action' => 'CREATE',
                'message_id' => $message->id,
                'sender_id' => $message->sender_id,
            ]
        );
    }
}
