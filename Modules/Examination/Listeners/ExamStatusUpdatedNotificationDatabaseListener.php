<?php


namespace Modules\Examination\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Examination\Events\ExamStatusUpdated;
use Modules\Notifications\Services\NotificationService;

class ExamStatusUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(
        ExamStatusUpdated $event
    ): void
    {

        $exam = $event->exam;

        $data = [
            'entity' => 'exam',
            'action' => 'STATUS_UPDATED',
            'exam_id' => $exam->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
        ];

        $this->notificationService->sendToRole(
            role: 'student',
            title: 'Exam Status Updated',
            body: "Exam {$exam->name} is now {$event->newStatus}.",
            type: 'exam_status_updated',
            data: $data
        );
    }
}
