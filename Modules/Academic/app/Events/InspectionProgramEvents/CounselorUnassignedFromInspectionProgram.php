<?php

namespace App\Events\InspectionProgramEvents;

use App\Entities\InspectionProgram;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CounselorUnassignedFromInspectionProgram
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InspectionProgram $program,
        public int $counselorId,
        public ?int $userId = null,
    ) {}
}
