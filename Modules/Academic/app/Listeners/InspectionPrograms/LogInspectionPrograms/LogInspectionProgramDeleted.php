<?php

namespace Modules\Academic\app\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramDeleted;

class LogInspectionProgramDeleted
{
    public function handle(InspectionProgramDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->log('Inspection program deleted');
    }
}
