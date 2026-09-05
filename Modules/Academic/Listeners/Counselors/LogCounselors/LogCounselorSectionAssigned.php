<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorSectionAssigned;

class LogCounselorSectionAssigned
{
    public function handle(CounselorSectionAssigned $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id'    => $event->counselor->id,
                'section_id'      => $event->sectionId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Counselor assigned to section');
    }
}
