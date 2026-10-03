<?php

namespace Modules\Academic\Listeners\LogSubjects;


use Modules\Academic\Events\SubjectsEvents\SubjectCreated;

class LogSubjectCreated
{
    public function handle(SubjectCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->subject)
            ->log('Subject created');
    }
}
