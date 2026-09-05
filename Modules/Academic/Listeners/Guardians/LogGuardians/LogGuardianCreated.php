<?php


namespace Modules\Academic\Listeners\Guardians\LogGuardians;

use Modules\Academic\Events\GuardianEvens\GuardianCreated;

class LogGuardianCreated
{
    public function handle(GuardianCreated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->guardian)
            ->log('Guardian created');
    }
}
