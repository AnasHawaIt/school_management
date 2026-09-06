<?php

namespace Modules\Attendance\Events\StudentAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\Entities\StudentAttendance;

class StudentAttendanceDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StudentAttendance $attendance,
        public ?int $userId = null,
    ) {
    }
}
