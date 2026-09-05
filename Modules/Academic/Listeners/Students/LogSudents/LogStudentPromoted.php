<?php

namespace Modules\Academic\Listeners\Students\LogSudents;

use Modules\Academic\Events\StudentEvents\StudentPromoted;

class LogStudentPromoted
{
    public function handle(StudentPromoted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->withProperties([
                'from_section_id' => $event->fromSectionId,
                'to_section_id'   => $event->toSectionId,
                'promoted_count'  => $event->promotedCount,
            ])
            ->log('Students promoted');
    }
}
