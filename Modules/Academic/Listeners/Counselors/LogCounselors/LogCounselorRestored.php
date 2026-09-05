<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorRestored;

class LogCounselorRestored
{
    public function handle(CounselorRestored $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
            ])
            ->log('Counselor restored');
    }
}
