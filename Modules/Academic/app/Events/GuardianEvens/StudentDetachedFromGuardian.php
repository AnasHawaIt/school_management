<?php

namespace App\Events\GuardianEvens;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Guardian;

class StudentDetachedFromGuardian
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Guardian $guardian,
        public int $studentId,
        public ?int $userId = null,
    ) {}
}
