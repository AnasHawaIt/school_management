<?php

namespace Modules\Academic\app\Listeners\LogSubjects;


use Modules\Academic\app\Events\SubjectsEvents\SubjectRestored;

class LogSubjectRestored
{
    public function handle(SubjectRestored $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->subject)
            ->log('Subject restored');
    }
}
