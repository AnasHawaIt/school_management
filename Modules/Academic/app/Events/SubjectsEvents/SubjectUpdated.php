<?php

namespace Modules\Academic\app\Events\SubjectsEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Subject;

class SubjectUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Subject $subject,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
