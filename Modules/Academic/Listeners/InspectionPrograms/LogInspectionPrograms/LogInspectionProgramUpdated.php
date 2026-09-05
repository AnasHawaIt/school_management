<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramUpdated;

class LogInspectionProgramUpdated
{
    public function handle(InspectionProgramUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->program)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Inspection program updated');
    }
}
