<?php

namespace App\Listeners\LeaveRequests;

use App\Events\LeaveRequests\LeaveRequestApproved;
use Illuminate\Contracts\Queue\ShouldQueue;

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
