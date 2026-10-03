<?php
namespace Modules\Academic\Events\TeacherAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\Entities\StudentAttendance;

class TeacherAttendanceDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StudentAttendance $attendance,
        public ?int $userId = null,
    ) {
    }
}
