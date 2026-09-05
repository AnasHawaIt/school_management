<?php
namespace Modules\Academic\Listeners\Students\LogSudents;

use Modules\Academic\Events\StudentEvents\StudentRestored;

class LogStudentRestored
{
    public function handle(StudentRestored $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->student)
            ->log('Student restored');
    }
}
