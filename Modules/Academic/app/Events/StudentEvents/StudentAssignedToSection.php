<?php
namespace App\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Student;
class StudentAssignedToSection
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Student $student,
        public int $sectionId,
        public int $semesterId,
        public int $academicYearId,
        public ?int $userId = null,
    ) {}
}
