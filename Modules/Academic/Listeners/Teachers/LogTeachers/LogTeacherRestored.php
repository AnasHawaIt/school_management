<?php

namespace Modules\Academic\Listeners\Teachers\LogTeachers;



use Modules\Academic\Events\TeacherEvents\TeacherRestored;

class LogTeacherRestored
{
    public function handle(TeacherRestored $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'employee_id' => $event->teacher->employee_id,
            ])
            ->log('Teacher restored');
    }
}
