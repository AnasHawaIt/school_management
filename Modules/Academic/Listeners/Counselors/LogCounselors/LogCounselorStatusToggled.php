<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorStatusToggled;

class LogCounselorStatusToggled
{
    public function handle(CounselorStatusToggled $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
                'old_status'   => $event->oldStatus,
                'new_status'   => $event->newStatus,
            ])
            ->log('Counselor status toggled');
    }
}
