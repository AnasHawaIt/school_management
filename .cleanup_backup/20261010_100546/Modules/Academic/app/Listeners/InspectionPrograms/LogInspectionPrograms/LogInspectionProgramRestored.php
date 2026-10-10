<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramRestored;

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
