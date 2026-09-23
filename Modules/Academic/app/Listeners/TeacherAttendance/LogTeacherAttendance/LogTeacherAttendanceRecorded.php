<?php

namespace Modules\Academic\app\Listeners\TeacherAttendance\LogTeacherAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\TeacherAttendance\TeacherAttendanceRecorded;

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
