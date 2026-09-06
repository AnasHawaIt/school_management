<?php

namespace Modules\Attendance\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestUpdated;

class LogLeaveRequestUpdated implements ShouldQueue
{
    public function handle(LeaveRequestUpdated $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->request)
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('Leave request updated');
    }
}
