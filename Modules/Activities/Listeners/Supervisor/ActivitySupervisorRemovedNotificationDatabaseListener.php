<?php


namespace Modules\Activities\Listeners\Supervisor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\Events\ActivitySupervisorRemoved;
use Modules\Notifications\Services\NotificationService;

class ActivitySupervisorRemovedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivitySupervisorRemoved $event): void
    {
        $supervisor = $event->supervisor;

        $activity = $supervisor->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'SUPERVISOR_REMOVED',
            'activity_id' => $activity->id,
            'supervisor_id' => $supervisor->id,
            'teacher_id' => $supervisor->teacher_id,
        ];

        $this->notificationService->sendToRole(
            role: 'teacher',
            title: 'Activity Supervisor Removed',
            body: "A supervisor was removed from {$activity->title}",
            type: 'activity_supervisor_removed',
            data: $data
        );
    }
}
