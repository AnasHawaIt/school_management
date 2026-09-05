<?php

namespace Modules\Academic\Listeners\InspectionPrograms\LogInspectionPrograms;

use Modules\Academic\Events\InspectionProgramEvents\ObservationSubmitted;

class LogObservationSubmitted
{
    public function handle(ObservationSubmitted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->program)
            ->withProperties([
                'counselor_id' => $event->counselorId,
                'changes'      => $event->changes,
            ])
            ->log('Inspection observation submitted');
    }
}
