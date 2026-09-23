<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;

use Modules\Academic\app\Events\StudentEvents\StudentStatusUpdated;

class LogStudentStatusUpdated
{
    public function handle(StudentStatusUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->student)
            ->withProperties([
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
            ])
            ->log('Student status updated');
    }
}
