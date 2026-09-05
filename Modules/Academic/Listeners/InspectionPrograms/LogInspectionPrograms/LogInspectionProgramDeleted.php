<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramDeleted;

class LogInspectionProgramDeleted
{
    public function handle(InspectionProgramDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->program)
            ->log('Inspection program deleted');
    }
}
