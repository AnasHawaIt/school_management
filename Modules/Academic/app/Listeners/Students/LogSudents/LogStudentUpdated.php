<?php

namespace App\Listeners\Students\LogSudents;

use App\Events\StudentEvents\StudentUpdated;

class LogStudentUpdated
{
    public function handle(StudentUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->student)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Student updated');
    }
}
