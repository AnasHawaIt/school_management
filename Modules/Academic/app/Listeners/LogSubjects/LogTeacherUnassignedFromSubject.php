<?php


namespace Modules\Academic\app\Listeners\LogSubjects;

use Modules\Academic\app\Events\SubjectsEvents\TeacherUnassignedFromSubject;

class LogTeacherUnassignedFromSubject
{
    public function handle(TeacherUnassignedFromSubject $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->subject)
            ->withProperties([
                'teacher_id'       => $event->teacherId,
                'section_id'       => $event->sectionId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Teacher unassigned from subject');
    }
}
