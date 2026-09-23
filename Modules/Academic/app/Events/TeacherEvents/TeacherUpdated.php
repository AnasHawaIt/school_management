<?php

namespace Modules\Academic\app\Events\TeacherEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Teacher;

class TeacherUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Teacher $teacher,
        public array   $changes = [],
        public ?int    $userId = null,
    )
    {
    }
}

