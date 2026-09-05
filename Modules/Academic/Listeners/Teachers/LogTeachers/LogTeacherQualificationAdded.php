<?php

namespace Modules\Academic\Listeners\Teachers\LogTeachers;


use Modules\Academic\Events\TeacherEvents\QualificationAdded;

class LogTeacherQualificationAdded
{
    public function handle(QualificationAdded $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->teacher)
            ->withProperties([
                'teacher_id' => $event->teacher->id,
                'qualification_id' => $event->qualification->id,
                'qualification' => $event->qualification->toArray(),
            ])
            ->log('Teacher qualification added');
    }
}
