<?php

namespace Modules\Academic\app\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Counselor;

class CounselorUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
