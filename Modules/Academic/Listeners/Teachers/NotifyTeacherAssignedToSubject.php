<?php

namespace Modules\Academic\Listeners\Teachers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\SubjectsEvents\TeacherAssignedToSubject;
use Modules\Notifications\Services\NotificationService;

class NotifyTeacherAssignedToSubject implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        TeacherAssignedToSubject $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->teacher->user_id],
            title: 'Subject assigned',
            body: 'A new subject has been assigned to you.',
            type: 'teacher_assigned_to_subject',
            data: [
                'entity' => 'subject_teacher',
                'action' => 'TEACHER_ASSIGNED',
                'teacher_id' => $event->teacher->id,
                'subject_id' => $event->subjectId,
                'assigned_by' => $event->userId,
            ]
        );
    }
}
