<?php

namespace Modules\Attendance\Listeners\TeacherAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\Events\TeacherAttendance\TeacherAttendanceRecorded;

class LogTeacherAttendanceRecorded implements ShouldQueue
{
    public function handle(TeacherAttendanceRecorded $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->attendance)
            ->log('Teacher attendance recorded');
    }
}
