<?php


namespace Modules\Examination\Listeners;

use Modules\Examination\Events\ExamRestored;

class LogExamRestored
{
    public function handle(ExamRestored $event): void
    {
        $exam = $event->exam;

        activity()
            ->performedOn($exam)
            ->causedBy(auth()->user())
            ->withProperties([
                'exam_id' => $exam->id,
                'name' => $exam->name,
                'subject_id' => $exam->subject_id,
                'section_id' => $exam->section_id,
                'exam_date' => $exam->exam_date,
                'start_time' => $exam->start_time,
            ])
            ->log('Exam.Restored');
    }
}
