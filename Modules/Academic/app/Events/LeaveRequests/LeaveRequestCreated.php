<?php

namespace App\Events\LeaveRequests;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\Entities\LeaveRequest;

class LeaveRequestCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public LeaveRequest $request,
        public ?int $userId = null,
    ) {
    }
}
