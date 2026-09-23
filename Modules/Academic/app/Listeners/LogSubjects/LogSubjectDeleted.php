<?php

namespace Modules\Academic\app\Listeners\LogSubjects;


use Modules\Academic\app\Events\SubjectsEvents\SubjectDeleted;

class LogSubjectDeleted
{
    public function handle(SubjectDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->subject)
            ->log('Subject deleted');
    }
}
