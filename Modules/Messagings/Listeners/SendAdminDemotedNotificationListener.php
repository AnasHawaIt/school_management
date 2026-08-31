<?php


namespace Modules\Messagings\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\Events\AdminDemoted;
use Modules\Notifications\Services\NotificationService;

class SendAdminDemotedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(
        AdminDemoted $event
    ): void
    {

        $this->notificationService->sendToUsers(
            userIds: [$event->user->id],
            title: 'Admin privileges removed',
            body: 'Your conversation admin privileges have been removed.',
            type: 'admin_demoted',
            data: [
                'entity' => 'conversation',
                'action' => 'ADMIN_DEMOTED',
                'conversation_id' => $event->conversation->id,
                'user_id' => $event->user->id,
                'demoted_by' => $event->demotedBy,
            ]
        );
    }
}
