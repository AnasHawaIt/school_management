<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;


use Modules\Academic\app\Events\StudentEvents\StudentDeleted;

class LogStudentDeleted
{
    public function handle(StudentDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->student)
            ->log('Student deleted');
    }
}
