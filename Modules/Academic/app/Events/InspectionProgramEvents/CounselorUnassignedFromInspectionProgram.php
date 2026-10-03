<?php

namespace Modules\Academic\Events\InspectionProgramEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\InspectionProgram;

class CounselorUnassignedFromInspectionProgram
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InspectionProgram $program,
        public int $counselorId,
        public ?int $userId = null,
    ) {}
}
