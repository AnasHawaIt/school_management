<?php

namespace App\Listeners\LogSubjects;


use App\Events\SubjectsEvents\SubjectRestored;

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
