<?php

namespace App\Listeners\Students;

use App\Events\StudentEvents\StudentDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentDeleted implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentDeleted $event
    ): void {
        $event->student->loadMissing('guardians');

        $userIds = [];

        foreach ($event->student->guardians as $guardian) {
            if ($guardian->user_id) {
                $userIds[] = $guardian->user_id;
            }
        }

        $userIds = array_values(array_unique($userIds));

        if (empty($userIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: $userIds,
            title: 'Student account deactivated',
            body: 'The student account has been deactivated.',
            type: 'academic',
            data: [
                'entity' => 'student',
                'action' => 'STUDENT_DELETED',
                'student_id' => $event->student->id,
                'deleted_by' => $event->userId,
            ]
        );
    }
}
