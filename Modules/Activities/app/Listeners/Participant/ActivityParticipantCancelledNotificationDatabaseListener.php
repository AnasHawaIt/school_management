<?php


namespace Modules\Activities\app\Listeners\Participant;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityParticipantCancelled;
use Modules\Notifications\app\Services\NotificationService;

class ActivityParticipantCancelledNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityParticipantCancelled $event): void
    {
        $participant = $event->participant;

        $activity = $participant->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'PARTICIPANT_CANCELLED',
            'activity_id' => $activity->id,
            'participant_id' => $participant->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Participant Cancelled',
            body: "A participant cancelled registration for {$activity->title}",
            type: 'activity_participant_cancelled',
            data: $data
        );
    }
}
