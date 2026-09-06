<?php

namespace Modules\Attendance\Listeners\StudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceUpdated;

class LogStudentAttendanceUpdated implements ShouldQueue
{
    public function handle(StudentAttendanceUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->attendance)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Student attendance updated');
    }
}
