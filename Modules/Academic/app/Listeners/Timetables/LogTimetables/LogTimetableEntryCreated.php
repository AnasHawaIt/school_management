<?php

namespace Modules\Academic\app\Listeners\Timetables\LogTimetables;

use Modules\Academic\app\Events\TimetableEvents\TimetableEntryCreated;

class LogTimetableEntryCreated
{
    public function handle(TimetableEntryCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->timetable)
            ->withProperties([
                'section_id' => $event->timetable->section_id,
                'teacher_id' => $event->timetable->teacher_id,
                'subject_id' => $event->timetable->subject_id,
                'semester_id' => $event->timetable->semester_id,
                'day' => $event->timetable->day,
                'start_time' => $event->timetable->start_time,
                'end_time' => $event->timetable->end_time,
            ])
            ->log('Timetable entry created');
    }
}
