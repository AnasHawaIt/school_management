<?php


namespace App\Listeners\StudentPoints\LogStudentPoints;


use App\Events\StudentPointEvents\StudentPointsBulkGiven;

class LogStudentPointsBulkGiven
{
    public function handle(StudentPointsBulkGiven $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'student_ids' => $event->studentIds,
                'students_count' => count($event->studentIds),
                'point_category_id' => $event->pointCategoryId,
                'points' => $event->points,
            ])
            ->log('Student points given in bulk');
    }
}
