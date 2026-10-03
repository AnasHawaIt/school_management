<?php

namespace Modules\Academic\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Counselor;

class CounselorDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public ?int $userId = null,
    ) {}
}
