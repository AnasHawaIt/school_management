<?php

namespace Modules\Academic\Listeners\LogSubjects;


use Modules\Academic\Events\SubjectsEvents\SubjectDeleted;

class LogSubjectDeleted
{
    public function handle(SubjectDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->subject)
            ->log('Subject deleted');
    }
}
