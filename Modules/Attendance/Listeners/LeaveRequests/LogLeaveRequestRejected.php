<?php

namespace Modules\Attendance\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestRejected;

class LogLeaveRequestRejected implements ShouldQueue
{
    public function handle(LeaveRequestRejected $event): void
    {
        activity()
            ->causedBy($event->reviewerId)
            ->performedOn($event->request)
            ->withProperties([
                'notes' => $event->notes,
            ])
            ->log('Leave request rejected');
    }
}
