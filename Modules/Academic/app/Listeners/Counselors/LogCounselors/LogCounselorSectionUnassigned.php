<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorSectionUnassigned;

class LogCounselorSectionUnassigned
{
    function handle(CounselorSectionUnassigned $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id'    => $event->counselor->id,
                'section_id'      => $event->sectionId,
                'academic_year_id' => $event->academicYearId,
            ])
            ->log('Counselor unassigned from section');
    }
}
