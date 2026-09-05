<?php

namespace Modules\Academic\Events\StudentPointEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\StudentPoint;

class StudentPointGiven
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StudentPoint $studentPoint,
        public ?int $userId = null,
    ) {}
}
