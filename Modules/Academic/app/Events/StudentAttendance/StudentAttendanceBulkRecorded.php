<?php
namespace Modules\Academic\app\Events\StudentAttendance;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentAttendanceBulkRecorded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $sectionId,
        public string $date,
        public array $records,
        public ?int $userId = null,
    ) {}
}
