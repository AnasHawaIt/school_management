<?php

namespace Modules\Academic\app\Events\LeaveRequests;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\Entities\LeaveRequest;

class LeaveRequestRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public LeaveRequest $request,
        public int $reviewerId,
        public ?string $notes = null,
    ) {
    }
}
