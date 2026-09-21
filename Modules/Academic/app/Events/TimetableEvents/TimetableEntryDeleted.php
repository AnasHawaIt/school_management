<?php

namespace App\Events\TimetableEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Timetable;

class TimetableEntryDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Timetable $timetable,
        public ?int $userId = null,
    ) {}
}
