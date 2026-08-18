<?php

namespace Modules\School\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\School\Events\SectionUpdated;
use Modules\Notifications\Services\NotificationService;
use Modules\Academic\Entities\Student;

class SectionUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(SectionUpdated $event): void
    {
        $section = $event->section;

        $userIds = Student::query()
            ->where('current_section_id', $section->id)
            ->pluck('user_id')
            ->toArray();

        if (empty($userIds)) {
            return;
        }

        $data = [
            'entity' => 'section',
            'action' => 'UPDATE',
            'section_id' => $section->id,
            'class_id' => $section->class_id,
        ];

        $this->notificationService->sendToUsers(
            userIds: $userIds,
            title: 'Section Updated',
            body: "The information of section {$section->name} has been updated.",
            type: 'section_updated',
            data: $data
        );
    }
}
