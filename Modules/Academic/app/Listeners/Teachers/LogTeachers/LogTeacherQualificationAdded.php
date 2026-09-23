<?php

namespace Modules\Academic\app\Listeners\Teachers\LogTeachers;

use Modules\Academic\app\Events\TeacherEvents\QualificationAdded;

class LogTeacherQualificationAdded
{
    public function handle(QualificationAdded $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'qualification_id' => $event->qualification->id,
                'qualification' => $event->qualification->toArray(),
            ])
            ->log('Teacher qualification added');
    }
}
