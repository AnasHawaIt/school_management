<?php

namespace Modules\Academic\app\Listeners\Counselors\LogCounselors;

use Modules\Academic\app\Events\CounselorEvents\CounselorCreated;

class LogCounselorCreated
{
    public function handle(CounselorCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
                'user_id'      => $event->counselor->user_id,
            ])
            ->log('Counselor created');
    }
}
