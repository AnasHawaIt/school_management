<?php

namespace Modules\Academic\app\Listeners\Students\LogSudents;

use Modules\Academic\app\Events\StudentEvents\MedicalRecordUpdated;

class LogMedicalRecordUpdated
{
    public function handle(MedicalRecordUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->student)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Student medical record updated');
    }
}
