<?php

namespace Modules\Academic\app\Listeners\StudentAttendance\LogStudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceBulkRecorded;

class LogStudentAttendanceBulkRecorded implements ShouldQueue
{
    public function handle(StudentAttendanceBulkRecorded $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'section_id' => $event->sectionId,
                'date' => $event->date,
            ])
            ->log('Student attendance recorded in bulk');
    }
}
