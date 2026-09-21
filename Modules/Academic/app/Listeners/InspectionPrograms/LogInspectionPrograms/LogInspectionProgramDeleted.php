<?php

namespace App\Listeners\InspectionPrograms\LogInspectionPrograms;

use App\Events\InspectionProgramEvents\InspectionProgramDeleted;

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
