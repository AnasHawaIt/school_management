<?php

namespace Modules\Academic\Listeners\TeacherAttendance\LogTeacherAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\TeacherAttendance\TeacherAttendanceDeleted;

class LogTeacherAttendanceDeleted implements ShouldQueue
{
    public function handle(TeacherAttendanceDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attendance)
            ->log('Teacher attendance deleted');
    }
}
