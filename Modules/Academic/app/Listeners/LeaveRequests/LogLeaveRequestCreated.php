<?php

namespace App\Listeners\LeaveRequests;

use App\Events\LeaveRequests\LeaveRequestCreated;
use Illuminate\Contracts\Queue\ShouldQueue;


class LogLeaveRequestCreated implements ShouldQueue
{
    public function handle(LeaveRequestCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->request)
            ->log('Leave request created');
    }
}
