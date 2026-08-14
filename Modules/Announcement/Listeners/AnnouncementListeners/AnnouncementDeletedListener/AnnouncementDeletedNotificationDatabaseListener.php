<?php

namespace Modules\Announcement\Listeners\AnnouncementListeners\AnnouncementDeletedListener;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Notifications\Services\NotificationService;

class AnnouncementDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(AnnouncementDeleted $event){


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
            'action' => 'Deleted',
            'announcement_id' => $announcement->id,
        ];

        if ($role) {

            $this->notificationService->sendToRole(
                role: $role,
                title: $announcement->title,
                body: $announcement->body,
                type: 'announcement_deleted',
                data: $data
            );

            return;
        }

        $this->notificationService->sendToAll(
            title: $announcement->title,
            body: $announcement->body,
            type: 'announcement_deleted',
            data: $data
        );
    }
}
