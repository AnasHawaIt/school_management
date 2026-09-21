<?php

namespace Modules\Academic\app\Listeners\StudentPoints;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointDeleted;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentPointDeleted implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentPointDeleted $event
    ): void {
        $event->studentPoint->loadMissing('guardians');

        $userIds = [];

        if ($event->studentPoint->user_id) {
            $userIds[] = $event->studentPoint->user_id;
        }

        foreach ($event->studentPoint->guardians as $guardian) {
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
            title: 'Student point removed',
            body: 'A previously recorded student point has been removed.',
            type: 'academic',
            data: [
                'entity' => 'student_point',
                'action' => 'STUDENT_POINT_DELETED',
                'student_id' => $event->studentPoint->student()->id,
                'student_point_id' => $event->studentPoint->id,
                'deleted_by' => $event->userId,
            ]
        );
    }
}
