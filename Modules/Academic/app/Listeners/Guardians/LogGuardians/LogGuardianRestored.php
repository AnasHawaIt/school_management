<?php

namespace Modules\Academic\Listeners\Guardians\LogGuardians;

use Modules\Academic\Events\GuardianEvens\GuardianRestored;

class LogGuardianRestored
{
    public function handle(GuardianRestored $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->guardian)
            ->log('Guardian restored');
    }
}
