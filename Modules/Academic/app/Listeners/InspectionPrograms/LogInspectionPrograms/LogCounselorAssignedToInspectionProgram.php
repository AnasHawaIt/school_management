<?php

namespace Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\app\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;

class LogCounselorAssignedToInspectionProgram
{
    public function handle(
        CounselorAssignedToInspectionProgram $event
    ): void {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->withProperties([
                'counselor_id' => $event->counselorId,
                'role'         => $event->role,
            ])
            ->log('Counselor assigned to inspection program');
    }
}
