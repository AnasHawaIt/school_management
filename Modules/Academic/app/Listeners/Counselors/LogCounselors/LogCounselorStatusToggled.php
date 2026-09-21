<?php

namespace App\Listeners\Counselors\LogCounselors;

use App\Events\CounselorEvents\CounselorStatusToggled;

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
