<?php

namespace Modules\Academic\app\Listeners\Counselors\LogCounselors;

use Modules\Academic\app\Events\CounselorEvents\CounselorSectionAssigned;

class LogCounselorSectionAssigned
{
    function handle(CounselorSectionAssigned $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'counselor_id'    => $event->counselor->id,
                'section_id'      => $event->sectionId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Counselor assigned to section');
    }
}
