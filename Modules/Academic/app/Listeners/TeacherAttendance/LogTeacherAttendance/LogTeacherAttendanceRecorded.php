<?php

namespace App\Listeners\TeacherAttendance\LogTeacherAttendance;

use App\Events\TeacherAttendance\TeacherAttendanceDeleted;
use App\Events\TeacherAttendance\TeacherAttendanceRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;

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
