<?php

namespace App\Events\InspectionProgramEvents;

use App\Entities\InspectionProgram;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InspectionProgramUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public InspectionProgram $program,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
