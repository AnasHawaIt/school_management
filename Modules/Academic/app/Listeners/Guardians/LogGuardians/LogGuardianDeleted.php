<?php

namespace Modules\Academic\Listeners\Guardians\LogGuardians;

use Modules\Academic\Events\GuardianEvens\GuardianDeleted;

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
