<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementUpdatedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Notifications\Services\NotificationService;

class AnnouncementUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(AnnouncementUpdated $event)
    {
        $announcement = $event->announcement;

        $map = [
            'students' => 'student',
            'teachers' => 'teacher',
            'parents'  => 'parent',
            'public'   => null,
        ];

        $audience = $announcement->audience;

        if (!array_key_exists($audience, $map)) {
            throw new \InvalidArgumentException(
                "Invalid announcement audience: {$audience}"
            );
        }

        $role = $map[$audience];

        $data = [
            'entity' => 'announcement',
            'action' => 'Update',
            'announcement_id' => $announcement->id,
        ];

        if ($role) {

            $this->notificationService->sendToRole(
                role: $role,
                title: $announcement->title,
                body: $announcement->body,
                type: 'announcement_updated',
                data: $data
            );

            return;
        }

        $this->notificationService->sendToAll(
            title: $announcement->title,
            body: $announcement->body,
            type: 'announcement_updated',
            data: $data
        );
    }
    }
