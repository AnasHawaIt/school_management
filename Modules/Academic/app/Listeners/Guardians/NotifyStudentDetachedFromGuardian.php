<?php

namespace Modules\Academic\app\Listeners\Guardians;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\GuardianEvens\StudentDetachedFromGuardian;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentDetachedFromGuardian implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentDetachedFromGuardian $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->guardian->user_id],
            title: 'Student removed',
            body: 'A student has been removed from your guardian account.',
            type: 'student_detached_from_guardian',
            data: [
                'entity' => 'guardian_student',
                'action' => 'STUDENT_DETACHED',
                'guardian_id' => $event->guardian->id,
                'student_id' => $event->studentId,
                'detached_by' => $event->userId,
            ]
        );
    }
}
