<?php

namespace Modules\Academic\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\LeaveRequests\LeaveRequestCreated;


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
