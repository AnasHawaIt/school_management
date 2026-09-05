<?php

namespace Modules\Academic\Listeners\Guardians\LogGuardians;

use Modules\Academic\Events\GuardianEvens\GuardianRestored;

class LogGuardianRestored
{
    public function handle(GuardianRestored $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->guardian)
            ->log('Guardian restored');
    }
}
