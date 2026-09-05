<?php

namespace Modules\Academic\Listeners\Guardians\LogGuardians;

use Modules\Academic\Events\GuardianEvens\GuardianUpdated;

class LogGuardianUpdated
{
    public function handle(GuardianUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->guardian)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Guardian updated');
    }
}
