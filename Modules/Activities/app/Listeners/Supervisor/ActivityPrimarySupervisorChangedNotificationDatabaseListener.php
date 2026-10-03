<?php


namespace Modules\Activities\Listeners\Supervisor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\Events\ActivityPrimarySupervisorChanged;
use Modules\Notifications\Services\NotificationService;

class ActivityPrimarySupervisorChangedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityPrimarySupervisorChanged $event): void
    {
        $supervisor = $event->supervisor;

        $activity = $supervisor->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'PRIMARY_SUPERVISOR_CHANGED',
            'activity_id' => $activity->id,
            'supervisor_id' => $supervisor->id,
            'teacher_id' => $supervisor->teacher_id,
        ];

        $this->notificationService->sendToRole(
            role: 'teacher',
            title: 'Primary Supervisor Changed',
            body: "The primary supervisor of {$activity->title} has changed",
            type: 'activity_primary_supervisor_changed',
            data: $data
        );
    }
}
