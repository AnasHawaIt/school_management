<?php

namespace App\Listeners\Teachers\LogTeachers;

use App\Events\TeacherEvents\TeacherStatusToggled;

class LogTeacherStatusToggled
{
    public function handle(TeacherStatusToggled $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
            ])
            ->log('Teacher status toggled');
    }
}
