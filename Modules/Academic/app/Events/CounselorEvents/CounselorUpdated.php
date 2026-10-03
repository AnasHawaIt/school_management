<?php

namespace Modules\Academic\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Counselor;

class CounselorUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
