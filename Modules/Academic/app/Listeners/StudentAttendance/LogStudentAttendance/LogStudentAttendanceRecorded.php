<?php

namespace Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceRecorded;

class LogStudentAttendanceRecorded implements ShouldQueue
{
    public function handle(StudentAttendanceRecorded $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'attendance_id' => $event->attendance->id,
                'date' => $event->attendance,
            ])
            ->log('Student attendance recorded in bulk');
    }
}
