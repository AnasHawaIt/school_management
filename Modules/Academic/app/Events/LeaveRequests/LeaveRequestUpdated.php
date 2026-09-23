<?php

namespace App\Events\LeaveRequests;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\Entities\LeaveRequest;

class LeaveRequestUpdated
{

    use Dispatchable, SerializesModels;

    public function __construct(
        public LeaveRequest $request,
        public array $changes = [],
        public ?int $userId = null,
    ) {
    }
}
