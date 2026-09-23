<?php
namespace Modules\Academic\app\Events\TeacherAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Attendance\app\Entities\TeacherAttendance;

class TeacherAttendanceRecorded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TeacherAttendance $attendance,
        public ?int $userId = null,
    ) {
    }
}
