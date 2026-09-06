<?php

namespace Modules\Attendance\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestDeleted;

class LogLeaveRequestDeleted implements ShouldQueue
{
    public function handle(LeaveRequestDeleted $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->request)
            ->log('Leave request deleted');
    }
}
