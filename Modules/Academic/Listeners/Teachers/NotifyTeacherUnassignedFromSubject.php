<?php

namespace Modules\Academic\Listeners\Teachers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\SubjectsEvents\TeacherUnassignedFromSubject;
use Modules\Notifications\Services\NotificationService;

class NotifyTeacherUnassignedFromSubject implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        TeacherUnassignedFromSubject $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->teacher->user_id],
            title: 'Subject assignment removed',
            body: 'Your assignment to a subject has been removed.',
            type: 'teacher_unassigned_from_subject',
            data: [
                'entity' => 'subject_teacher',
                'action' => 'TEACHER_UNASSIGNED',
                'teacher_id' => $event->teacher->id,
                'subject_id' => $event->subjectId,
                'unassigned_by' => $event->userId,
            ]
        );
    }
}
