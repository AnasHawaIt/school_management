<?php

namespace Modules\Attendance\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestCreated;

class LogLeaveRequestCreated implements ShouldQueue
{
    public function handle(LeaveRequestCreated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->request)
            ->log('Leave request created');
    }
}
