<?php

namespace App\Events\StudentEvents;

use App\Entities\Student;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

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
