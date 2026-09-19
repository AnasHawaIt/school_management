<?php


namespace Modules\Activities\app\Listeners\Activity;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityStarted;
use Modules\Notifications\app\Services\NotificationService;

class ActivityStartedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityStarted $event): void
    {
        $activity = $event->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'START',
            'activity_id' => $activity->id,
        ];

        $this->notificationService->sendToAll(
            title: 'Activity Started',
            body: $activity->title,
            type: 'activity_started',
            data: $data
        );
    }
}
