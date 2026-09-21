<?php

namespace App\Events\CounselorEvents;

use App\Entities\Counselor;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CounselorUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
