<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;

use Modules\Academic\app\Events\StudentEvents\StudentAssignedToSection;

class LogStudentAssignedToSection
{
    public function handle(StudentAssignedToSection $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->student)
            ->withProperties([
                'section_id'       => $event->sectionId,
                'semester_id'      => $event->semesterId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Student assigned to section');
    }
}
