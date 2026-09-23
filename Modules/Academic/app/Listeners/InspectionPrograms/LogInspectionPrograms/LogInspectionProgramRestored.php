<?php

namespace Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramRestored;

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
