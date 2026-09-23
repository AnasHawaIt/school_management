<?php


namespace Modules\Academic\app\Listeners\LogSubjects;

use Modules\Academic\app\Events\SubjectsEvents\TeacherAssignedToSubject;

class LogTeacherAssignedToSubject
{
    public function handle(TeacherAssignedToSubject $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->subject)
            ->withProperties([
                'teacher_id'       => $event->teacherId,
                'section_id'       => $event->sectionId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Teacher assigned to subject');
    }
}
