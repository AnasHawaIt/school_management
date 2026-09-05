<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorUpdated;

class LogCounselorUpdated
{
    public function handle(CounselorUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->counselor)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Counselor updated');
    }
}
