<?php

namespace Modules\Attendance\Listeners\StudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceDeleted;

class LogStudentAttendanceDeleted implements ShouldQueue
{
    public function handle(StudentAttendanceDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->attendance)
            ->log('Student attendance deleted');
    }
}
