<?php


namespace Modules\Activities\app\Listeners\Activity;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\app\Events\ActivityCreated;
use Modules\Notifications\app\Services\NotificationService;

class ActivityCreatedNotificationListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ActivityCreated $event): void
    {
        $activity = $event->activity;

        $data = [
            'entity' => 'activity',
            'action' => 'CREATE',
            'activity_id' => $activity->id,
        ];

        $this->notificationService->sendToRole(
            role: 'admin',
            title: 'New Activity',
            body: $activity->title,
            type: 'activity_created',
            data: $data
        );
    }
}
