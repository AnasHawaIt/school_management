<?php

namespace Modules\Academic\Listeners\LogSubjects;

use Modules\Academic\Events\SubjectsEvents\TeacherAssignedToSubject;

class LogTeacherAssignedToSubject
{
    public function handle(TeacherAssignedToSubject $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->subject)
            ->withProperties([
                'teacher_id'       => $event->teacherId,
                'section_id'       => $event->sectionId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Teacher assigned to subject');
    }
}
