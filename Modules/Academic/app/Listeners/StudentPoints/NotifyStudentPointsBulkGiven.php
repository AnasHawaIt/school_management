<?php

namespace Modules\Academic\app\Listeners\StudentPoints;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\StudentPointEvents\StudentPointsBulkGiven;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentPointsBulkGiven implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentPointsBulkGiven $event
    ): void {
        $userIds = [];

        foreach ($event->studentIds as $student) {
            if ($student->user_id) {
                $userIds[] = $student->user_id;
            }

            $student->loadMissing('guardians');

            foreach ($student->guardians as $guardian) {
                if ($guardian->user_id) {
                    $userIds[] = $guardian->user_id;
                }
            }
        }

        $userIds = array_values(array_unique($userIds));

        if (empty($userIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: $userIds,
            title: 'New student points',
            body: 'New points have been recorded for one or more students.',
            type: 'academic',
            data: [
                'entity' => 'student_point',
                'action' => 'STUDENT_POINTS_BULK_GIVEN',
                'given_by' => auth()->id(),
            ]
        );
    }
}
