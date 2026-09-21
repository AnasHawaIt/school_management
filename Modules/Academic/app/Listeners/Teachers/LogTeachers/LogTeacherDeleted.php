<?php

namespace App\Listeners\Teachers\LogTeachers;

use App\Events\TeacherEvents\TeacherDeleted;

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
