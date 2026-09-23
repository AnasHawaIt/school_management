<?php

namespace Modules\Academic\app\Listeners\Teachers\LogTeachers;

use Modules\Academic\app\Events\TeacherEvents\QualificationDeleted;

class LogTeacherQualificationDeleted
{
    public function handle(QualificationDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'qualification_id' => $event->qualification->id,
                'teacher_id' => $event->qualification->teacher_id,
                'qualification' => $event->qualification->toArray(),
            ])
            ->log('Teacher qualification deleted');
    }
}
