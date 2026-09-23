<?php

namespace Modules\Academic\app\Listeners\Teachers\LogTeachers;

use Modules\Academic\app\Events\TeacherEvents\TeacherDeleted;

class LogTeacherDeleted
{
    public function handle(TeacherDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id'  => $event->teacher->id,
                'employee_id' => $event->teacher->employee_id,
            ])
            ->log('Teacher deleted');
    }
}
