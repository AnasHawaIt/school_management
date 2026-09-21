<?php

namespace App\Listeners\Teachers\LogTeachers;

use App\Events\TeacherEvents\TeacherRestored;

class LogTeacherRestored
{
    public function handle(TeacherRestored $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'employee_id' => $event->teacher->employee_id,
            ])
            ->log('Teacher restored');
    }
}
