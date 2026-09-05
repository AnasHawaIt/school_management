<?php

namespace Modules\Academic\Listeners\Teachers\LogTeachers;

use Modules\Academic\Events\TeacherEvents\TeacherStatusToggled;

class LogTeacherStatusToggled
{
    public function handle(TeacherStatusToggled $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
            ])
            ->log('Teacher status toggled');
    }
}
