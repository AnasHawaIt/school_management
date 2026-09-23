<?php

namespace Modules\Academic\app\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestCreated;


class LogLeaveRequestCreated implements ShouldQueue
{
    public function handle(LeaveRequestCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->request)
            ->log('Leave request created');
    }
}
