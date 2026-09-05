<?php

namespace Modules\Academic\Listeners\Teachers\LogTeachers;

use Modules\Academic\Events\TeacherEvents\QualificationDeleted;

class LogTeacherQualificationDeleted
{
    public function handle(QualificationDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->withProperties([
                'qualification_id' => $event->qualification->id,
                'teacher_id' => $event->qualification->teacher_id,
                'qualification' => $event->qualification->toArray(),
            ])
            ->log('Teacher qualification deleted');
    }
}
