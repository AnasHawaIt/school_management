<?php

namespace App\Events\TeacherEvents;

use App\Entities\Teacher;
use App\Entities\TeacherQualification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeacherStatusToggled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Teacher $teacher,
        public string $oldStatus,
        public string $newStatus,
        public ?int $userId = null,
    ) {}
}
