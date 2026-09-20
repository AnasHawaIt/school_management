<?php


namespace Modules\Messagings\app\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\app\Events\ParticipantAdded;
use Modules\Notifications\app\Services\NotificationService;

class SendParticipantAddedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(
        ParticipantAdded $event
    ): void
    {

        $this->notificationService->sendToUsers(
            userIds: [$event->user->id],
            title: 'Added to conversation',
            body: 'You have been added to a conversation.',
            type: 'participant_added',
            data: [
                'entity' => 'conversation',
                'action' => 'PARTICIPANT_ADDED',
                'conversation_id' => $event->conversation->id,
                'user_id' => $event->user->id,
                'added_by' => $event->addedBy,
            ]
        );
    }
}
