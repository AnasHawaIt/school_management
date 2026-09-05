<?php

namespace Modules\Academic\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Modules\Academic\Entities\Counselor;

class CounselorStatusToggled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public string $oldStatus,
        public string $newStatus,
        public ?int $userId = null,
    ) {}
}
