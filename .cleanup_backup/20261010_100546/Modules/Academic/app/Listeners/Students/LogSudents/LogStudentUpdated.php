<?php

namespace Modules\Academic\Listeners\Students\LogSudents;

use Modules\Academic\Events\StudentEvents\StudentUpdated;

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
