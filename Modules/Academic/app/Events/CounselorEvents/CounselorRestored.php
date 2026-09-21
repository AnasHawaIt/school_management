<?php

namespace Modules\Academic\app\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Counselor;

class CounselorRestored
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public ?int $userId = null,
    ) {}
}
