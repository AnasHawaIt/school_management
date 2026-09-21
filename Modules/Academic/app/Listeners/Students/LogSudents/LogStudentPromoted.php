<?php

namespace App\Listeners\Students\LogSudents;


use App\Events\StudentEvents\StudentPromoted;

class LogStudentPromoted
{
    public function handle(StudentPromoted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'from_section_id' => $event->fromSectionId,
                'to_section_id'   => $event->toSectionId,
                'promoted_count'  => $event->promotedCount,
            ])
            ->log('Students promoted');
    }
}
