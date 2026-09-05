<?php

namespace Modules\Academic\Listeners\Teachers\LogTeachers;

use Modules\Academic\Events\TeacherEvents\TeacherCreated;

class LogTeacherCreated
{
    public function handle(TeacherCreated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id'  => $event->teacher->id,
                'employee_id' => $event->teacher->employee_id,
            ])
            ->log('Teacher created');
    }
}
