<?php

namespace App\Listeners\InspectionPrograms\LogInspectionPrograms;

use App\Events\InspectionProgramEvents\InspectionProgramSetCurrent;

class LogInspectionProgramSetCurrent
{
    public function handle(
        InspectionProgramSetCurrent $event
    ): void {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->withProperties([
                'inspection_program_id' => $event->program->id,
            ])
            ->log('Inspection program set as current');
    }
}
