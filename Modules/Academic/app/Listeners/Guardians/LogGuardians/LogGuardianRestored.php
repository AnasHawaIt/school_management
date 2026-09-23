<?php

namespace Modules\Academic\app\Listeners\Guardians\LogGuardians;

use Modules\Academic\app\Events\GuardianEvens\GuardianRestored;

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
