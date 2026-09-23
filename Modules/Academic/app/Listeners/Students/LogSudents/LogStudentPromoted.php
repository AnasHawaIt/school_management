<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;


use Modules\Academic\app\Events\StudentEvents\StudentPromoted;

class LogStudentPromoted
{
    public function handle(StudentPromoted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'from_section_id' => $event->fromSectionId,
                'to_section_id'   => $event->toSectionId,
            ])
            ->log('Students promoted');
    }
}
