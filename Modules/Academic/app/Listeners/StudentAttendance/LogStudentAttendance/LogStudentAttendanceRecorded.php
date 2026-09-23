<?php

namespace App\Listeners\StudentAttendance\LogStudentAttendance;

use App\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use App\Events\StudentAttendance\StudentAttendanceRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;

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
