<?php

namespace Modules\Academic\app\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestUpdated;

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
