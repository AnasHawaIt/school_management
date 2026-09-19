<?php


namespace Modules\Messagings\app\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\app\Events\ParticipantRemoved;
use Modules\Notifications\app\Services\NotificationService;

class SendParticipantRemovedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(
        ParticipantRemoved $event
    ): void
    {

        $this->notificationService->sendToUsers(
            userIds: [$event->user->id],
            title: 'Removed from conversation',
            body: 'You have been removed from a conversation.',
            type: 'participant_removed',
            data: [
                'entity' => 'conversation',
                'action' => 'PARTICIPANT_REMOVED',
                'conversation_id' => $event->conversation->id,
                'removed_by' => $event->removedBy,
            ]
        );
    }
}
