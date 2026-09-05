<?php

namespace Modules\Academic\Listeners\Students\LogSudents;

use Modules\Academic\Events\StudentEvents\StudentAssignedToSection;

class LogStudentAssignedToSection
{
    public function handle(StudentAssignedToSection $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->student)
            ->withProperties([
                'section_id'       => $event->sectionId,
                'semester_id'      => $event->semesterId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Student assigned to section');
    }
}
