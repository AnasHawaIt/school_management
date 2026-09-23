<?php

namespace Modules\Academic\app\Listeners\Teachers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\SubjectsEvents\TeacherUnassignedFromSubject;
use Modules\Notifications\app\Services\NotificationService;

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
            userIds: [$event->subject->user_id],
            title: 'Subject assignment removed',
            body: 'Your assignment to a subject has been removed.',
            type: 'teacher_unassigned_from_subject',
            data: [
                'entity' => 'subject_teacher',
                'action' => 'TEACHER_UNASSIGNED',
                'teacher_id' => $event->teacherId,
                'subject_id' => $event->subject->id,
                'unassigned_by' => auth()->id()
            ]
        );
    }
}
