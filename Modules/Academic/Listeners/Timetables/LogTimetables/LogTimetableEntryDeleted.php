<?php


namespace Modules\Academic\Listeners\Timetables\LogTimetables;

use Modules\Academic\Events\TimetableEvents\TimetableEntryDeleted;

class LogTimetableEntryDeleted
{
    public function handle(TimetableEntryDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->timetable)
            ->withProperties([
                'section_id' => $event->timetable->section_id,
                'teacher_id' => $event->timetable->teacher_id,
                'subject_id' => $event->timetable->subject_id,
                'semester_id' => $event->timetable->semester_id,
            ])
            ->log('Timetable entry deleted');
    }
}
