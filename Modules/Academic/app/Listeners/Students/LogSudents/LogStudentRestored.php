<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;


use Modules\Academic\app\Events\StudentEvents\StudentRestored;

class LogStudentRestored
{
    public function handle(StudentRestored $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->student)
            ->log('Student restored');
    }
}
