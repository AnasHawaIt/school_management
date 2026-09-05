<?php

namespace Modules\Academic\Listeners\Teachers\LogTeachers;


use Modules\Academic\Events\TeacherEvents\TeacherUpdated;

class LogTeacherUpdated
{
    public function handle(TeacherUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'changes' => $event->changes,
            ])
            ->log('Teacher updated');
    }
}
