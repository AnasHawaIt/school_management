<?php

namespace App\Listeners\Students\LogSudents;


use App\Events\StudentEvents\StudentDeleted;

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
