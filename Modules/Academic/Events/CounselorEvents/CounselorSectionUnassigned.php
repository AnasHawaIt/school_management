<?php

namespace Modules\Academic\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\Entities\Counselor;

class CounselorSectionUnassigned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public int $sectionId,
        public int $academicYearId,
        public ?int $userId = null,
    ) {}
}
