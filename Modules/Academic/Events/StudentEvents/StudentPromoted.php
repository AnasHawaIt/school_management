<?php

namespace Modules\Academic\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentPromoted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $fromSectionId,
        public int $toSectionId,
        public int $promotedCount,
        public ?int $userId = null,
    ) {}
}
