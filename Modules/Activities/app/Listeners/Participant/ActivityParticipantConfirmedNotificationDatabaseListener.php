<?php


namespace Modules\Activities\app\Listeners\Participant;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityParticipantConfirmed;
use Modules\Notifications\app\Services\NotificationService;

class ActivityParticipantConfirmedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityParticipantConfirmed $event): void
    {
        $participant = $event->participant;

        $activity = $participant->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'PARTICIPANT_CONFIRMED',
            'activity_id' => $activity->id,
            'participant_id' => $participant->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Participant Confirmed',
            body: "A participant was confirmed for {$activity->title}",
            type: 'activity_participant_confirmed',
            data: $data
        );
    }
}
