<?php

namespace Modules\Academic\app\Listeners\LogSubjects;


use Modules\Academic\app\Events\SubjectsEvents\SubjectCreated;

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
