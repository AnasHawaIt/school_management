<?php

namespace Modules\Academic\Listeners\LogSubjects;


use Modules\Academic\Events\SubjectsEvents\SubjectUpdated;

class LogSubjectUpdated
{
    public function handle(SubjectUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->subject)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Subject updated');
    }
}
