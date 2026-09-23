<?php

namespace Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\app\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;

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
