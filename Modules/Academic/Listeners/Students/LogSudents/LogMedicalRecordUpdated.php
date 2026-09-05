<?php

namespace Modules\Academic\Listeners\Students\LogSudents;

use Modules\Academic\Events\StudentEvents\MedicalRecordUpdated;

class LogMedicalRecordUpdated
{
    public function handle(MedicalRecordUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->student)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Student medical record updated');
    }
}
