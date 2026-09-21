<?php

namespace Modules\Academic\app\Events\CounselorEvents;

use App\Entities\Counselor;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CounselorCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public ?int $userId = null,
    ) {}
}
