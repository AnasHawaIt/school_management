<?php

namespace Modules\Academic\app\Events\GuardianEvens;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Guardian;

class StudentAttachedToGuardian
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Guardian $guardian,
        public int $studentId,
        public array $pivotData = [],
        public ?int $userId = null,
    ) {}
}
