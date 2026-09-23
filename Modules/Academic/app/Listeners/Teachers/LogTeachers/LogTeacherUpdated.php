<?php

namespace Modules\Academic\app\Listeners\Teachers\LogTeachers;


use Modules\Academic\app\Events\TeacherEvents\TeacherUpdated;

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
