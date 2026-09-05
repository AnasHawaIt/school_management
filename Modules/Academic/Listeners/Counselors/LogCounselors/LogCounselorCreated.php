<?php

namespace Modules\Academic\Listeners\Counselors\LogCounselors;

use Modules\Academic\Events\CounselorEvents\CounselorCreated;

class LogCounselorCreated
{
    public function handle(CounselorCreated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->counselor)
            ->withProperties([
                'counselor_id' => $event->counselor->id,
                'user_id'      => $event->counselor->user_id,
            ])
            ->log('Counselor created');
    }
}
