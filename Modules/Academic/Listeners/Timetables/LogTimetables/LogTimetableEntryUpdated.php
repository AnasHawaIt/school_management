<?php


namespace Modules\Academic\Listeners\Timetables\LogTimetables;

use Modules\Academic\Events\TimetableEvents\TimetableEntryUpdated;

class LogTimetableEntryUpdated
{
    public function handle(TimetableEntryUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->timetable)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Timetable entry updated');
    }
}
