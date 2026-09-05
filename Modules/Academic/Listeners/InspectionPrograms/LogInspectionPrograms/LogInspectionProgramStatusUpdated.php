<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\InspectionProgramStatusUpdated;

class LogInspectionProgramStatusUpdated
{
    public function handle(
        InspectionProgramStatusUpdated $event
    ): void {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->program)
            ->withProperties([
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
            ])
            ->log('Inspection program status updated');
    }
}
