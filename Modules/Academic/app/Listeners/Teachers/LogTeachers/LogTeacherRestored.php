<?php

namespace Modules\Academic\app\Listeners\Teachers\LogTeachers;

use Modules\Academic\app\Events\TeacherEvents\TeacherRestored;

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
