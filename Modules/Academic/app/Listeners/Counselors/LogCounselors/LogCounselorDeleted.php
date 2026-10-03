<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorDeleted;

class LogCounselorDeleted
{
    function handle(CounselorDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
            ])
            ->log('Counselor deleted');
    }
}
