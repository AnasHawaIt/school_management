<?php

namespace Modules\Academic\app\Listeners\Teachers\LogTeachers;

use App\Events\TeacherEvents\TeacherCreated;

class LogTeacherCreated
{
    public function handle(TeacherCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id'  => $event->teacher->id,
                'employee_id' => $event->teacher->employee_id,
            ])
            ->log('Teacher created');
    }
}
