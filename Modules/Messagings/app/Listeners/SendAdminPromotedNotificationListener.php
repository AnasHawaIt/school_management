<?php


namespace Modules\Messagings\app\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\app\Events\AdminPromoted;
use Modules\Notifications\app\Services\NotificationService;

class SendAdminPromotedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(
        AdminPromoted $event
    ): void
    {

        $this->notificationService->sendToUsers(
            userIds: [$event->user->id],
            title: 'You are now an admin',
            body: 'You have been promoted to conversation admin.',
            type: 'admin_promoted',
            data: [
                'entity' => 'conversation',
                'action' => 'ADMIN_PROMOTED',
                'conversation_id' => $event->conversation->id,
                'user_id' => $event->user->id,
                'promoted_by' => $event->promotedBy,
            ]
        );
    }
}
