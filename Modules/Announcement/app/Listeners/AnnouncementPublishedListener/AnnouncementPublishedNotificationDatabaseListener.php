<?php

namespace Modules\Announcement\app\Listeners\AnnouncementPublishedListener;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Announcement\app\Enums\AnnouncementAudience;
use Modules\Announcement\app\Events\AnnouncementPublished;
use Modules\Notifications\app\Services\NotificationService;

class AnnouncementPublishedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(AnnouncementPublished $event): void
    {
        $announcement = $event->announcement;

        $data = [
            'entity' => 'announcement',
            'action' => 'published',
            'announcement_id' => $announcement->id,
        ];

        match ($announcement->audience) {

            AnnouncementAudience::ALL =>
            $this->notificationService->sendToAll(
                title: $announcement->title,
                body: $announcement->body,
                type: 'announcement_published',
                data: $data
            ),

            AnnouncementAudience::ADMIN =>
            $this->notificationService->sendToRole(
                role: 'admin',
                title: $announcement->title,
                body: $announcement->body,
                type: 'announcement_published',
                data: $data
            ),

            AnnouncementAudience::STUDENT =>
            $this->notificationService->sendToRole(
                role: 'student',
                title: $announcement->title,
                body: $announcement->body,
                type: 'announcement_published',
                data: $data
            ),

            AnnouncementAudience::TEACHER =>
            $this->notificationService->sendToRole(
                role: 'teacher',
                title: $announcement->title,
                body: $announcement->body,
                type: 'announcement_published',
                data: $data
            ),

            AnnouncementAudience::PARENT =>
            $this->notificationService->sendToRole(
                role: 'parent',
                title: $announcement->title,
                body: $announcement->body,
                type: 'announcement_published',
                data: $data
            ),
        };
    }
}
