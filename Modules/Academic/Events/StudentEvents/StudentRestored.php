<?php


namespace Modules\Academic\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Student;

class StudentRestored
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Student $student,
        public ?int    $userId = null,
    )
    {
    }
}
