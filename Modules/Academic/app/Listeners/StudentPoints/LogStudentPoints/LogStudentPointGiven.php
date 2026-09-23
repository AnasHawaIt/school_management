<?php


namespace Modules\Academic\app\Listeners\StudentPoints\LogStudentPoints;


use Modules\Academic\app\Events\StudentPointEvents\StudentPointGiven;

class LogStudentPointGiven
{
    public function handle(StudentPointGiven $event): void
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
            ->log('Student point given');
    }
}
