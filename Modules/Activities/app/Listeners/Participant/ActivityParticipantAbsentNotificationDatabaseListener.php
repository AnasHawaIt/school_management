<?php


namespace Modules\Activities\Listeners\Participant;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\Events\ActivityParticipantAbsent;
use Modules\Notifications\Services\NotificationService;

class ActivityParticipantAbsentNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityParticipantAbsent $event): void
    {
        $participant = $event->participant;

        $activity = $participant->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'PARTICIPANT_ABSENT',
            'activity_id' => $activity->id,
            'participant_id' => $participant->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Attendance Updated',
            body: "A participant was marked absent for {$activity->title}",
            type: 'activity_participant_absent',
            data: $data
        );
    }
}
