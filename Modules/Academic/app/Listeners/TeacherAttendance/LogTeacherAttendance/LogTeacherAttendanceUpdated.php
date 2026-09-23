<?php

namespace Modules\Academic\app\Listeners\TeacherAttendance\LogTeacherAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\TeacherAttendance\TeacherAttendanceUpdated;

class LogTeacherAttendanceUpdated implements ShouldQueue
{
        public function handle(TeacherAttendanceUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attendance)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Teacher attendance updated');
    }
}
