<?php


namespace Modules\Activities\Listeners\Activity;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activities\Events\ActivityCreated;
use Modules\Notifications\Services\NotificationService;

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
