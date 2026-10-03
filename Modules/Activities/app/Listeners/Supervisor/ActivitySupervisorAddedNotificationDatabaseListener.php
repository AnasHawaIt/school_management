<?php


namespace Modules\Activities\Listeners\Supervisor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\Events\ActivitySupervisorAdded;
use Modules\Notifications\Services\NotificationService;

class ActivitySupervisorAddedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivitySupervisorAdded $event): void
    {
        $supervisor = $event->supervisor;

        $activity = $supervisor->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'SUPERVISOR_ADDED',
            'activity_id' => $activity->id,
            'supervisor_id' => $supervisor->id,
            'teacher_id' => $supervisor->teacher_id,
        ];

        $this->notificationService->sendToRole(
            role: 'teacher',
            title: 'Activity Supervisor Added',
            body: "A supervisor was added to {$activity->title}",
            type: 'activity_supervisor_added',
            data: $data
        );
    }
}
