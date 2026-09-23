<?php

namespace Modules\Academic\app\Listeners\Guardians\LogGuardians;

use Modules\Academic\app\Events\GuardianEvens\GuardianCreated;

class LogGuardianCreated
{
    public function handle(GuardianCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->guardian)
            ->log('Guardian created');
    }
}
