<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;

class LogCounselorUnassignedFromInspectionProgram
{
    public function handle(
        CounselorUnassignedFromInspectionProgram $event
    ): void {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->withProperties([
                'counselor_id' => $event->counselorId,
            ])
            ->log('Counselor unassigned from inspection program');
    }
}
