<?php


namespace Modules\Examination\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Examination\Events\ExamDeleted;
use Modules\Notifications\app\Services\NotificationService;

class ExamDeletedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ExamDeleted $event): void
    {
        $exam = $event->exam;

        $data = [
            'entity' => 'exam',
            'action' => 'DELETE',
            'exam_id' => $exam->id,
            'subject_id' => $exam->subject_id,
            'section_id' => $exam->section_id,
        ];

        $this->notificationService->sendToRole(
            role: 'student',
            title: 'Exam Cancelled',
            body: "The exam {$exam->name} has been deleted.",
            type: 'exam_deleted',
            data: $data
        );
    }
}
