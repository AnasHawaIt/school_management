<?php

namespace Modules\Academic\app\Listeners\Guardians\LogGuardians;

use Modules\Academic\app\Events\GuardianEvens\GuardianUpdated;

class LogGuardianUpdated
{
    public function handle(GuardianUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->guardian)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Guardian updated');
    }
}
