<?php

namespace App\Listeners\Teachers\LogTeachers;


use App\Events\TeacherEvents\TeacherUpdated;

class LogTeacherUpdated
{
    public function handle(TeacherUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'changes' => $event->changes,
            ])
            ->log('Teacher updated');
    }
}
