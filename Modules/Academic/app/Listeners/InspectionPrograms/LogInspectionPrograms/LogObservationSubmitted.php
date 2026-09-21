<?php

namespace App\Listeners\InspectionPrograms\LogInspectionPrograms;

use App\Events\InspectionProgramEvents\ObservationSubmitted;

class LogObservationSubmitted
{
    public function handle(ObservationSubmitted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->withProperties([
                'counselor_id' => $event->counselorId,
                'changes'      => $event->changes,
            ])
            ->log('Inspection observation submitted');
    }
}
