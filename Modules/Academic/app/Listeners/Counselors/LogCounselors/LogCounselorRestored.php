<?php

namespace App\Listeners\Counselors\LogCounselors;

use Modules\Academic\app\Events\CounselorEvents\CounselorRestored;

class LogCounselorRestored
{
    function handle(CounselorRestored $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
            ])
            ->log('Counselor restored');
    }
}
