<?php

namespace App\Listeners\StudentAttendance\LogStudentAttendance;

use App\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use App\Events\StudentAttendance\StudentAttendanceUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogStudentAttendanceUpdated implements ShouldQueue
{
    public function handle(StudentAttendanceUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attendance)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Student attendance updated');
    }
}
