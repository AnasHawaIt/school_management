<?php

namespace App\Listeners\LogSubjects;


use App\Events\SubjectsEvents\SubjectDeleted;

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
