<?php


namespace Modules\Activities\app\Listeners\Participant;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityParticipantAttended;
use Modules\Notifications\app\Services\NotificationService;

class ActivityParticipantAttendedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityParticipantAttended $event): void
    {
        $participant = $event->participant;

        $activity = $participant->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'PARTICIPANT_ATTENDED',
            'activity_id' => $activity->id,
            'participant_id' => $participant->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Attendance Recorded',
            body: "Attendance was recorded for {$activity->title}",
            type: 'activity_participant_attended',
            data: $data
        );
    }
}
