<?php

namespace Modules\Academic\app\Events\GuardianEvens;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Guardian;

class StudentDetachedFromGuardian
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Guardian $guardian,
        public int $studentId,
        public ?int $userId = null,
    ) {}
}
