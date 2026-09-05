<?php

namespace Modules\Academic\Listeners\StudentPoints;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\StudentPointEvents\StudentPointDeleted;
use Modules\Notifications\Services\NotificationService;

class NotifyStudentPointDeleted implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentPointDeleted $event
    ): void {
        $event->student->loadMissing('guardians');

        $userIds = [];

        if ($event->student->user_id) {
            $userIds[] = $event->student->user_id;
        }

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
            title: 'Student point removed',
            body: 'A previously recorded student point has been removed.',
            type: 'academic',
            data: [
                'entity' => 'student_point',
                'action' => 'STUDENT_POINT_DELETED',
                'student_id' => $event->student->id,
                'student_point_id' => $event->studentPointId,
                'deleted_by' => $event->userId,
            ]
        );
    }
}
