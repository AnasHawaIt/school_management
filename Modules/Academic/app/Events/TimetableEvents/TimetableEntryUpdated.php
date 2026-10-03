<?php

namespace Modules\Academic\Events\TimetableEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Timetable;

class TimetableEntryUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Timetable $timetable,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
