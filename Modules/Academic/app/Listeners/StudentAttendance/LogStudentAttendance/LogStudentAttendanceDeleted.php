<?php

namespace App\Listeners\StudentAttendance\LogStudentAttendance;

use App\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use App\Events\StudentAttendance\StudentAttendanceDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogStudentAttendanceDeleted implements ShouldQueue
{
    public function handle(StudentAttendanceDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->attendance)
            ->log('Student attendance deleted');
    }
}
