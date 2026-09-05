<?php

namespace Modules\Academic\Listeners\LogSubjects;

use Modules\Academic\Events\SubjectsEvents\SubjectRestored;

class LogSubjectRestored
{
    public function handle(SubjectRestored $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->subject)
            ->log('Subject restored');
    }
}
