<?php

namespace App\Listeners\LeaveRequests;

use App\Events\LeaveRequests\LeaveRequestApproved;
use App\Events\LeaveRequests\LeaveRequestDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;

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
