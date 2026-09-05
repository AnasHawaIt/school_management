<?php

namespace Modules\Academic\Listeners\Students;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\StudentEvents\StudentAssignedToSection;
use Modules\Notifications\Services\NotificationService;

class NotifyStudentAssignedToSection implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentAssignedToSection $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->student->user_id],
            title: 'Section assigned',
            body: 'You have been assigned to a section.',
            type: 'student_assigned_to_section',
            data: [
                'entity' => 'student_section',
                'action' => 'SECTION_ASSIGNED',
                'student_id' => $event->student->id,
                'section_id' => $event->sectionId,
                'assigned_by' => $event->userId,
            ]
        );
    }
}
