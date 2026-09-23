<?php
namespace Modules\Academic\app\Events\TeacherAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\app\Entities\StudentAttendance;

class TeacherAttendanceDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StudentAttendance $attendance,
        public ?int $userId = null,
    ) {
    }
}
