<?php

namespace App\Events\SubjectsEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Subject;

class SubjectRestored
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Subject $subject,
        public ?int $userId = null,
    ) {}
}
