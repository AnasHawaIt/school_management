<?php

namespace Modules\Academic\Listeners\Students;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\StudentEvents\StudentTransferred;
use Modules\Notifications\Services\NotificationService;

class NotifyStudentTransferred implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentTransferred $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->student->user_id],
            title: 'Student section changed',
            body: 'Your section has been changed successfully.',
            type: 'student_transferred',
            data: [
                'entity' => 'student',
                'action' => 'TRANSFERRED',
                'student_id' => $event->student->id,
                'from_section_id' => $event->fromSectionId,
                'to_section_id' => $event->toSectionId,
                'transferred_by' => $event->userId,
            ]
        );
    }
}
