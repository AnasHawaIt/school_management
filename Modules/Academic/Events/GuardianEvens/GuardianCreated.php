<?php

namespace Modules\Academic\Events\GuardianEvens;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Guardian;

class GuardianCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Guardian $guardian,
        public ?int $userId = null,
    ) {}
}
