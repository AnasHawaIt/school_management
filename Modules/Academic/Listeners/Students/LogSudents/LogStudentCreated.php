<?php


namespace Modules\Academic\Listeners\Students\LogSudents;

use Modules\Academic\Events\StudentEvents\StudentCreated;

class LogStudentCreated
{
    public function handle(StudentCreated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->student)
            ->log('Student created');
    }
}
