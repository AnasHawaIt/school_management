<?php

namespace Modules\Academic\Events\TeacherEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Teacher;

class TeacherCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Teacher $teacher,
        public ?int $userId = null,
    ) {}
}
