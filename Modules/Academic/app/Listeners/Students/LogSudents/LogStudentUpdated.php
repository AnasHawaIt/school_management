<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;

use Modules\Academic\app\Events\StudentEvents\StudentUpdated;

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
