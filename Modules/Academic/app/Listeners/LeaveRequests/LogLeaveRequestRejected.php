<?php

namespace Modules\Academic\app\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestRejected;

class LogLeaveRequestRejected implements ShouldQueue
{
    public function handle(LeaveRequestRejected $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->request)
            ->withProperties([
                'notes' => $event->notes,
            ])
            ->log('Leave request rejected');
    }
}

