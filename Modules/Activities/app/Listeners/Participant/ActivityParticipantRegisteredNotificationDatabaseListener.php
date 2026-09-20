<?php


namespace Modules\Activities\app\Listeners\Participant;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityParticipantRegistered;
use Modules\Notifications\app\Services\NotificationService;

class ActivityParticipantRegisteredNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityParticipantRegistered $event): void
    {
        $participant = $event->participant;

        $activity = $participant->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'PARTICIPANT_REGISTERED',
            'activity_id' => $activity->id,
            'participant_id' => $participant->id,
        ];

        $teacherIds = $activity->supervisors()
            ->pluck('teacher_id')
            ->unique()
            ->values()
            ->toArray();

        if (empty($teacherIds)) {
            return;
        }

        $this->notificationService->sendToRole(
            role: 'teacher',
            title: 'New Activity Registration',
            body: "A participant registered for {$activity->title}",
            type: 'activity_participant_registered',
            data: $data
        );
    }
}
