<?php

namespace Modules\Academic\Events\InspectionProgramEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\InspectionProgram;

class CounselorAssignedToInspectionProgram
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InspectionProgram $program,
        public int $counselorId,
        public string $role = 'member',
        public ?int $userId = null,
    ) {}
}
