<?php

namespace Modules\Attendance\Listeners\StudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\Events\StudentAttendance\StudentAttendanceBulkRecorded;

class LogStudentAttendanceBulkRecorded implements ShouldQueue
{
    public function handle(StudentAttendanceBulkRecorded $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->withProperties([
                'attendance_ids' => $event->attendanceIds,
                'section_id' => $event->sectionId,
                'date' => $event->date,
                'count' => count($event->attendanceIds),
            ])
            ->log('Student attendance recorded in bulk');
    }
}
