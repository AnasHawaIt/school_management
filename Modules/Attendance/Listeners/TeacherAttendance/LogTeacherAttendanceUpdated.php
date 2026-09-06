<?php

namespace Modules\Attendance\Listeners\TeacherAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\Events\TeacherAttendance\TeacherAttendanceUpdated;

class LogTeacherAttendanceUpdated implements ShouldQueue
{
    public function handle(TeacherAttendanceUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->attendance)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Teacher attendance updated');
    }
}
