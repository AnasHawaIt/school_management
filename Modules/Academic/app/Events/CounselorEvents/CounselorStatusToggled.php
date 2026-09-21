<?php

namespace App\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Counselor;

class CounselorStatusToggled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public string $oldStatus,
        public string $newStatus,
        public ?int $userId = null,
    ) {}
}
