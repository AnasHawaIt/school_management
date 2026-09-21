<?php

namespace App\Events\InspectionProgramEvents;

use App\Entities\InspectionProgram;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InspectionProgramStatusUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InspectionProgram $program,
        public string $oldStatus,
        public string $newStatus,
        public ?int $userId = null,
    ) {}
}
