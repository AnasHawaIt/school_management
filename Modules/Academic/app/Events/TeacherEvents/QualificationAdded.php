<?php

namespace Modules\Academic\app\Events\TeacherEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Teacher;
use Modules\Academic\app\Entities\TeacherQualification;

class QualificationAdded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Teacher $teacher,
        public TeacherQualification $qualification,
        public ?int $userId = null,
    ) {}
}
