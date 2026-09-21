<?php

namespace App\Listeners\Students;

use App\Events\StudentEvents\StudentDeleted;
use App\Events\StudentEvents\StudentPromoted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentPromoted implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentPromoted $event
    ): void {
        $userIds = $event->students
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($userIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: $userIds,
            title: 'Student promoted',
            body: 'Your academic promotion has been completed.',
            type: 'student_promoted',
            data: [
                'entity' => 'student',
                'action' => 'PROMOTED',
                'from_section_id' => $event->fromSectionId,
                'to_section_id' => $event->toSectionId,
                'promoted_by' => $event->userId,
            ]
        );
    }
}
