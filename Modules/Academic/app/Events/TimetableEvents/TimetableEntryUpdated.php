<?php

namespace App\Events\TimetableEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Timetable;

class TimetableEntryUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Timetable $timetable,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
