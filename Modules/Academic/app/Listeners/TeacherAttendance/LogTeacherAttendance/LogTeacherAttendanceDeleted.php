<?php

namespace App\Listeners\TeacherAttendance\LogTeacherAttendance;

use App\Events\TeacherAttendance\TeacherAttendanceDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;

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
