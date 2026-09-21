<?php

namespace App\Events\TeacherEvents;

use App\Entities\TeacherQualification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QualificationDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TeacherQualification $qualification,
        public ?int $userId = null,
    ) {}
}
