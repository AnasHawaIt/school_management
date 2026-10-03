<?php

namespace Modules\Academic\Events\TimetableEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Timetable;

class TimetableEntryDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Timetable $timetable,
        public ?int $userId = null,
    ) {}
}
