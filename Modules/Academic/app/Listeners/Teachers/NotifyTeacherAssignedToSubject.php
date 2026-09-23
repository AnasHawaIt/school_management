<?php

namespace Modules\Academic\app\Listeners\Teachers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\SubjectsEvents\TeacherAssignedToSubject;
use Modules\Notifications\app\Services\NotificationService;

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
            userIds: [$event->subject->user_id],
            title: 'Subject assigned',
            body: 'A new subject has been assigned to you.',
            type: 'teacher_assigned_to_subject',
            data: [
                'entity' => 'subject_teacher',
                'action' => 'TEACHER_ASSIGNED',
                'teacher_id' => $event->teacherId,
                'subject_id' => $event->subject->id,
                'assigned_by' => auth()->id(),
            ]
        );
    }
}
