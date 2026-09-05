<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorDeleted;

class LogCounselorDeleted
{
    public function handle(CounselorDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
            ])
            ->log('Counselor deleted');
    }
}
