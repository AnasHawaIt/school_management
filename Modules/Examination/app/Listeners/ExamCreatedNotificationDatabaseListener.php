<?php


namespace Modules\Examination\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Examination\Events\ExamCreated;
use Modules\Notifications\Services\NotificationService;

class ExamCreatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(ExamCreated $event): void
    {
        $exam = $event->exam;

        $data = [
            'entity' => 'exam',
            'action' => 'CREATE',
            'exam_id' => $exam->id,
            'subject_id' => $exam->subject_id,
            'section_id' => $exam->section_id,
            'exam_date' => $exam->exam_date,
            'start_time' => $exam->start_time,
        ];

        $this->notificationService->sendToRole(
            role: 'student',
            title: 'New Exam Scheduled',
            body: "A new exam has been scheduled: {$exam->name}",
            type: 'exam_created',
            data: $data
        );

        $this->notificationService->sendToRole(
            role: 'parent',
            title: 'New Exam Scheduled',
            body: "A new exam has been scheduled: {$exam->name}",
            type: 'exam_created',
            data: $data
        );
    }
}
