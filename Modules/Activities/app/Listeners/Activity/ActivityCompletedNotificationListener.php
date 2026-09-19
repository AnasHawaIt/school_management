<?php


namespace Modules\Activities\app\Listeners\Activity;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityCompleted;
use Modules\Notifications\app\Services\NotificationService;

class ActivityCompletedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityCompleted $event): void
    {
        $activity = $event->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'COMPLETE',
            'activity_id' => $activity->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Activity Completed',
            body: $activity->title,
            type: 'activity_completed',
            data: $data
        );
    }
}
