<?php

namespace Modules\Academic\app\Events\GuardianEvens;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Guardian;

class GuardianUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Guardian $guardian,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
