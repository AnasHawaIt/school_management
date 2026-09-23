<?php

namespace App\Events\StudentAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\app\Entities\StudentAttendance;
use Modules\Attendance\Entities\LeaveRequest;

class StudentAttendanceUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StudentAttendance $attendance,
        public array $changes = [],
        public ?int $userId = null,
    ) {
    }
}
