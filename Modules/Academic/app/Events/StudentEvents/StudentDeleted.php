<?php
namespace App\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Student;

class StudentDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Student $student,
        public ?int    $userId = null,
    )
    {
    }
}
