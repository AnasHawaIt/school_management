<?php

namespace Modules\Academic\app\Events\InspectionProgramEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\InspectionProgram;

class ObservationSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InspectionProgram $program,
        public int $counselorId,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
