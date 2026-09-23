<?php

namespace Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceUpdated;

class LogStudentAttendanceUpdated implements ShouldQueue
{
    public function handle(StudentAttendanceUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attendance)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Student attendance updated');
    }
}
