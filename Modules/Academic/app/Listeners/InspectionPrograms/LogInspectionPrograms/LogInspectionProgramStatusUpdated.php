<?php

namespace App\Listeners\InspectionPrograms\LogInspectionPrograms;

use App\Events\InspectionProgramEvents\InspectionProgramStatusUpdated;

class LogInspectionProgramStatusUpdated
{
    public function handle(
        InspectionProgramStatusUpdated $event
    ): void {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->withProperties([
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
            ])
            ->log('Inspection program status updated');
    }
}
