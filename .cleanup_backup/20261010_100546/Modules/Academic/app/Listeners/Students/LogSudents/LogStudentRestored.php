<?php

namespace Modules\Academic\Listeners\Students\LogSudents;


use Modules\Academic\Events\StudentEvents\StudentRestored;

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
