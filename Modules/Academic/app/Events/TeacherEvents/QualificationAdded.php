<?php

namespace Modules\Academic\Events\TeacherEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Teacher;
use Modules\Academic\Entities\TeacherQualification;

class QualificationAdded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Teacher $teacher,
        public TeacherQualification $qualification,
        public ?int $userId = null,
    ) {}
}
