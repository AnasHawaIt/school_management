<?php

namespace Modules\Attendance\Listeners\StudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceRecorded;

class LogStudentAttendanceRecorded implements ShouldQueue
{
    public function handle(StudentAttendanceRecorded $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->attendance)
            ->log('Student attendance recorded');
    }
}
