<?php

namespace App\Listeners\Timetables\LogTimetables;

use App\Events\TimetableEvents\TimetableEntryUpdated;

class LogTimetableEntryUpdated
{
    public function handle(TimetableEntryUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->timetable)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Timetable entry updated');
    }
}
