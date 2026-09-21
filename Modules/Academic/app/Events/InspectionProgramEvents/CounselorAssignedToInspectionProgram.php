<?php

namespace Modules\Academic\app\Events\InspectionProgramEvents;

use App\Entities\InspectionProgram;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
