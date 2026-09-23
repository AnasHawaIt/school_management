<?php

namespace Modules\Academic\app\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestDeleted;

class LogLeaveRequestDeleted implements ShouldQueue
{
    public function handle(LeaveRequestDeleted $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->request)
            ->log('Leave request deleted');
    }
}
