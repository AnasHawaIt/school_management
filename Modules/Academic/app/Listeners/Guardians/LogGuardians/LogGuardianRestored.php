<?php

namespace App\Listeners\Guardians\LogGuardians;

use App\Events\GuardianEvens\GuardianRestored;

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
