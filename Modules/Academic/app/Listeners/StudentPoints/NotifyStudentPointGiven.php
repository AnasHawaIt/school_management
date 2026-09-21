<?php

namespace App\Listeners\StudentPoints;

use App\Events\StudentPointEvents\StudentPointGiven;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentPointGiven implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentPointGiven $event
    ): void {
        $event->studentPoint->loadMissing('guardians');

        $userIds = [];

        // الطالب
        if ($event->studentPoint->user_id) {
            $userIds[] = $event->studentPoint->user_id;
        }

        // أولياء الأمور
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
            title: 'New student point',
            body: 'A new point has been recorded for the student.',
            type: 'academic',
            data: [
                'entity' => 'student_point',
                'action' => 'STUDENT_POINT_GIVEN',
                'student_id' => $event->studentPoint->student()->id,
                'student_point_id' => $event->studentPoint->id,
                'points' => $event->studentPoint->points,
                'category_id' => $event->studentPoint->category_id,
                'reason' => $event->studentPoint->reason,
                'given_by' => $event->userId,
            ]
        );
    }
}
