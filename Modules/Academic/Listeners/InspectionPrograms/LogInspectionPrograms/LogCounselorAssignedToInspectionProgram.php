<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;

class LogCounselorAssignedToInspectionProgram
{
    public function handle(
        CounselorAssignedToInspectionProgram $event
    ): void {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->program)
            ->withProperties([
                'counselor_id' => $event->counselorId,
                'role'         => $event->role,
            ])
            ->log('Counselor assigned to inspection program');
    }
}
