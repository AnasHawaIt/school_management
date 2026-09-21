<?php

namespace App\Listeners\Teachers\LogTeachers;

use App\Events\TeacherEvents\QualificationAdded;

class LogTeacherQualificationAdded
{
    public function handle(QualificationAdded $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'qualification_id' => $event->qualification->id,
                'qualification' => $event->qualification->toArray(),
            ])
            ->log('Teacher qualification added');
    }
}
