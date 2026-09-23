<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;

use Modules\Academic\app\Events\StudentEvents\StudentTransferred;

class LogStudentTransferred
{
    public function handle(StudentTransferred $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->student)
            ->withProperties([
                'from_section_id' => $event->fromSectionId,
                'to_section_id'   => $event->toSectionId,
            ])
            ->log('Student transferred to another section');
    }
}
