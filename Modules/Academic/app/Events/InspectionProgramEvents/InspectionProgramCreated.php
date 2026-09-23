<?php

namespace Modules\Academic\app\Events\InspectionProgramEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\InspectionProgram;

class InspectionProgramCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InspectionProgram $program,
        public ?int $userId = null,
    ) {}
}
