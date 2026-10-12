<?php


namespace Modules\Examination\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Examination\Events\ExamUpdated;
use Modules\Notifications\Services\NotificationService;

class ExamUpdatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ExamUpdated $event): void
    {
        $exam = $event->exam;

        $data = [
            'entity' => 'exam',
            'action' => 'UPDATE',
            'exam_id' => $exam->id,
            'subject_id' => $exam->subject_id,
            'section_id' => $exam->section_id,
            'exam_date' => $exam->exam_date,
            'start_time' => $exam->start_time,
            'end_time' => $exam->end_time,
            'room' => $exam->room,
        ];

        $this->notificationService->sendToRole(
            role: 'student',
            title: 'Exam Updated',
            body: "The exam {$exam->name} has been updated.",
            type: 'exam_updated',
            data: $data
        );
    }
}
