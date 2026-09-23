<?php

namespace App\Listeners\StudentAttendance\LogStudentAttendance;

use App\Events\StudentAttendance\StudentAttendanceBulkRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;

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
