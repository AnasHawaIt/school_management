<?php


namespace Modules\School\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Entities\Student;
use Modules\Notifications\app\Services\NotificationService;
use Modules\School\Events\SectionDeleted;

class SectionDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(SectionDeleted $event): void
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
            'action' => 'DELETE',
            'section_id' => $section->id,
            'class_id' => $section->class_id,
        ];

        $this->notificationService->sendToUsers(
            userIds: $userIds,
            title: 'Section Deleted',
            body: "Section {$section->name} has been deleted.",
            type: 'section_deleted',
            data: $data
        );
    }
}
