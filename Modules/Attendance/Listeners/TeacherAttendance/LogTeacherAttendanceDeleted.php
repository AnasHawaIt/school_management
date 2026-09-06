<?php

namespace Modules\Attendance\Listeners\TeacherAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\Events\TeacherAttendance\TeacherAttendanceDeleted;

class LogTeacherAttendanceDeleted implements ShouldQueue
{
    public function handle(TeacherAttendanceDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->attendance)
            ->log('Teacher attendance deleted');
    }
}
