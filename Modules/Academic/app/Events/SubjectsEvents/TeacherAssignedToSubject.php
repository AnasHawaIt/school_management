<?php

namespace Modules\Academic\app\Events\SubjectsEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Subject;

class TeacherAssignedToSubject
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Subject $subject,
        public int $teacherId,
        public int $sectionId,
        public int $academicYearId,
        public ?int $userId = null,
    ) {}
}
