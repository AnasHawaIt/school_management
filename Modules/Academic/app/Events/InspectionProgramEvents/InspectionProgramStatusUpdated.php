<?php

namespace Modules\Academic\Events\InspectionProgramEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\InspectionProgram;

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
