<?php

namespace Modules\Academic\app\Listeners\Counselors\LogCounselors;

use Modules\Academic\app\Events\CounselorEvents\CounselorUpdated;

class LogCounselorUpdated
{
    function handle(CounselorUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->counselor)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Counselor updated');
    }
}
