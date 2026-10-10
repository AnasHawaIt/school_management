<?php

namespace Modules\Academic\Listeners\StudentAttendance\LogStudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\StudentAttendance\StudentAttendanceDeleted;

class LogStudentAttendanceDeleted implements ShouldQueue
{
    public function handle(StudentAttendanceDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attendance)
            ->log('Student attendance deleted');
    }
}
