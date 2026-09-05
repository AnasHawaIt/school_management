<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramSetCurrent;

class LogInspectionProgramSetCurrent
{
    public function handle(
        InspectionProgramSetCurrent $event
    ): void {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->program)
            ->withProperties([
                'inspection_program_id' => $event->program->id,
            ])
            ->log('Inspection program set as current');
    }
}
