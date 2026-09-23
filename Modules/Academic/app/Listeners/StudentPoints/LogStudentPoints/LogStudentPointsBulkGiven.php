<?php


namespace Modules\Academic\app\Listeners\StudentPoints\LogStudentPoints;


use Modules\Academic\app\Events\StudentPointEvents\StudentPointsBulkGiven;

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
