<?php

namespace Modules\Academic\Events\SubjectsEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Subject;

class SubjectUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Subject $subject,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
