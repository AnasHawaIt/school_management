<?php

namespace Modules\Academic\app\Listeners\Guardians;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\GuardianEvens\StudentAttachedToGuardian;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentAttachedToGuardian implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentAttachedToGuardian $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->guardian->user_id],
            title: 'Student added',
            body: 'A student has been added to your guardian account.',
            type: 'student_attached_to_guardian',
            data: [
                'entity' => 'guardian_student',
                'action' => 'STUDENT_ATTACHED',
                'guardian_id' => $event->guardian->id,
                'student_id' => $event->studentId,
                'attached_by' => $event->userId,
            ]
        );
    }
}
