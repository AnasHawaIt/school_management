<?php

namespace Modules\Academic\Listeners\Teachers\LogTeachers;


use Modules\Academic\Events\TeacherEvents\TeacherDeleted;

class LogTeacherDeleted
{
    public function handle(TeacherDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'employee_id' => $event->teacher->employee_id,
            ])
            ->log('Teacher deleted');
    }
}
