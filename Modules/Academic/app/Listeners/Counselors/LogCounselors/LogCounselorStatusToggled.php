<?php

namespace Modules\Academic\app\Listeners\Counselors\LogCounselors;

use Modules\Academic\app\Events\CounselorEvents\CounselorStatusToggled;

class LogCounselorStatusToggled
{
    function handle(CounselorStatusToggled $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
                'old_status'   => $event->oldStatus,
                'new_status'   => $event->newStatus,
            ])
            ->log('Counselor status toggled');
    }
}
