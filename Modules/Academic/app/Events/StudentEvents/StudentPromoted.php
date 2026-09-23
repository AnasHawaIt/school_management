<?php

namespace Modules\Academic\app\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class StudentPromoted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Collection $students,
        public int $fromSectionId,
        public int $toSectionId,
        public ?int $userId = null,
    ) {}
}
