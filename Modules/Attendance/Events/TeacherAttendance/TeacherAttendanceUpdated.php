<?php

namespace Modules\Attendance\Events\TeacherAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\Entities\TeacherAttendance;

class TeacherAttendanceUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TeacherAttendance $attendance,
        public array $changes = [],
        public ?int $userId = null,
    ) {
    }
}
