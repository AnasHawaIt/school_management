<?php

namespace Modules\Academic\Listeners\TeacherAttendance\LogTeacherAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\TeacherAttendance\TeacherAttendanceRecorded;

class LogTeacherAttendanceRecorded implements ShouldQueue
{
    public function handle(TeacherAttendanceRecorded $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attendance)
            ->log('Teacher attendance recorded');
    }
}
