<?php

namespace App\Listeners\LeaveRequests;

use App\Events\LeaveRequests\LeaveRequestApproved;
use App\Events\LeaveRequests\LeaveRequestDeleted;
use App\Events\LeaveRequests\LeaveRequestUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogLeaveRequestUpdated implements ShouldQueue
{

    public function handle(LeaveRequestUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->request)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Leave request updated');
    }
}
