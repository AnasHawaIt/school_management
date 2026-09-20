<?php


namespace Modules\School\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Entities\Student;
use Modules\Notifications\app\Services\NotificationService;
use Modules\School\Events\SectionCreated;

class SectionCreatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(SectionCreated $event): void
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
            'action' => 'CREATE',
            'section_id' => $section->id,
            'class_id' => $section->class_id,
        ];

        $this->notificationService->sendToUsers(
            userIds: $userIds,
            title: 'New Section',
            body: "A new section {$section->name} has been created.",
            type: 'section_created',
            data: $data
        );
    }
}
