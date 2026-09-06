<?php

namespace Modules\Attendance\Events\TeacherAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\Entities\TeacherAttendance;

class TeacherAttendanceRecorded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TeacherAttendance $attendance,
        public ?int $userId = null,
    ) {
    }
}
