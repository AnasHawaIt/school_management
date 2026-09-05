<?php

namespace Modules\Academic\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Student;

class StudentUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Student $student,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
