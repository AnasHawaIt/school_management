<?php

namespace Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceDeleted;

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
