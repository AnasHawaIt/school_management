<?php

namespace Modules\Academic\app\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestApproved;

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
