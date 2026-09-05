<?php

namespace Modules\Academic\Listeners\Students\LogSudents;

use Modules\Academic\Events\StudentEvents\StudentStatusUpdated;

class LogStudentStatusUpdated
{
    public function handle(StudentStatusUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->student)
            ->withProperties([
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
            ])
            ->log('Student status updated');
    }
}
