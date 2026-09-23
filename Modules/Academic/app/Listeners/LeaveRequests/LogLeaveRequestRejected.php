<?php

namespace App\Listeners\LeaveRequests;

use App\Events\LeaveRequests\LeaveRequestCreated;
use App\Events\LeaveRequests\LeaveRequestRejected;
use Illuminate\Contracts\Queue\ShouldQueue;

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

