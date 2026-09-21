<?php

namespace App\Listeners\Students\LogSudents;


use App\Events\StudentEvents\StudentRestored;

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
