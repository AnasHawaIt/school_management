<?php


namespace Modules\Messagings\app\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Messagings\app\Events\ParticipantLeft;
use Modules\Notifications\app\Services\NotificationService;

class SendParticipantLeftNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(
        ParticipantLeft $event
    ): void
    {

        $adminIds = $event->conversation
            ->participants()
            ->whereIn('conversation_role', [
                'owner',
                'admin',
            ])
            ->pluck('users.id')
            ->reject(fn($id) => $id === $event->user->id)
            ->values()
            ->toArray();

        if (empty($adminIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: $adminIds,
            title: 'Participant left',
            body: $event->user->name . ' left the conversation.',
            type: 'participant_left',
            data: [
                'entity' => 'conversation',
                'action' => 'PARTICIPANT_LEFT',
                'conversation_id' => $event->conversation->id,
                'user_id' => $event->user->id,
            ]
        );
    }
}
