<?php

namespace Modules\Academic\Listeners\StudentPoints\LogStudentPoints;


use Modules\Academic\Events\StudentPointEvents\StudentPointsBulkGiven;

class LogStudentPointsBulkGiven
{
    public function handle(StudentPointsBulkGiven $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->withProperties([
                'student_ids' => $event->studentIds,
                'students_count' => count($event->studentIds),
                'point_category_id' => $event->pointCategoryId,
                'points' => $event->points,
            ])
            ->log('Student points given in bulk');
    }
}
