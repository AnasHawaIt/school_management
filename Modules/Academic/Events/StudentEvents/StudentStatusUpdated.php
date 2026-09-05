<?php

namespace Modules\Academic\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Student;

class StudentStatusUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Student $student,
        public string $oldStatus,
        public string $newStatus,
        public ?int $userId = null,
    ) {}
}
