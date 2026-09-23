<?php

namespace Modules\Academic\app\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\Counselor;

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
