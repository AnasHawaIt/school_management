<?php


namespace App\Listeners\StudentPoints\LogStudentPoints;


use Modules\Academic\app\Events\StudentPointEvents\StudentPointDeleted;

class LogStudentPointDeleted
{
    public function handle(StudentPointDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->studentPoint)
            ->withProperties([
                'student_id' => $event->studentPoint->student_id,
                'point_category_id' => $event->studentPoint->point_category_id,
                'points' => $event->studentPoint->points,
                'type' => $event->studentPoint->type,
                'reason' => $event->studentPoint->reason,
            ])
            ->log('Student point deleted');
    }
}
