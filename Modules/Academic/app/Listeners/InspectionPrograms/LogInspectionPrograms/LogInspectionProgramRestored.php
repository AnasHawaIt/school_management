<?php

namespace App\Listeners\InspectionPrograms\LogInspectionPrograms;

use App\Events\InspectionProgramEvents\InspectionProgramRestored;

class LogInspectionProgramRestored
{
    public function handle(InspectionProgramRestored $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->log('Inspection program restored');
    }
}
