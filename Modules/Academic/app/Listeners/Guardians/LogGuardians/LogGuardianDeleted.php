<?php

namespace App\Listeners\Guardians\LogGuardians;

use App\Events\GuardianEvens\GuardianDeleted;

class LogGuardianDeleted
{
    public function handle(GuardianDeleted $event): void
{
    activity()
        ->causedBy(auth()->user())
        ->performedOn($event->guardian)
        ->log('Guardian deleted');
}
}
