<?php

namespace Modules\Academic\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\LeaveRequests\LeaveRequestApproved;

class LogLeaveRequestApproved implements ShouldQueue
{
    public function handle(LeaveRequestApproved $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->request)
            ->withProperties([
                'notes' => $event->notes,
            ])
            ->log('Leave request approved');
    }
}
