<?php

namespace App\Listeners\TeacherAttendance\LogTeacherAttendance;

use App\Events\TeacherAttendance\TeacherAttendanceDeleted;
use App\Events\TeacherAttendance\TeacherAttendanceUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

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
