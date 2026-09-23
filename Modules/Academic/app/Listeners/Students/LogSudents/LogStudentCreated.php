<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;

use Modules\Academic\app\Events\StudentEvents\StudentCreated;

class LogStudentCreated
{
    public function handle(StudentCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->student)
            ->log('Student created');
    }
}
