<?php

namespace Modules\Academic\app\Events\TeacherEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\TeacherQualification;

class QualificationDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TeacherQualification $qualification,
        public ?int $userId = null,
    ) {}
}
