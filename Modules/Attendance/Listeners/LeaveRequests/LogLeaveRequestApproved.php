<?php

namespace Modules\Attendance\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestApproved;

class LogLeaveRequestApproved implements ShouldQueue
{
    public function handle(LeaveRequestApproved $event): void
    {
        activity()
            ->causedBy($event->reviewerId)
            ->performedOn($event->request)
            ->withProperties([
                'notes' => $event->notes,
            ])
            ->log('Leave request approved');
    }
}
