<?php

namespace Modules\Academic\app\Events\CounselorEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Counselor;

class CounselorSectionAssigned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Counselor $counselor,
        public int $sectionId,
        public int $academicYearId,
        public ?int $userId = null,
    ) {}
}
