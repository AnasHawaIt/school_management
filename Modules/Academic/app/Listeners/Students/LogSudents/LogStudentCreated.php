<?php

namespace App\Listeners\Students\LogSudents;

use App\Events\StudentEvents\StudentCreated;

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
