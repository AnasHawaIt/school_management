<?php


namespace Modules\Academic\app\Events\StudentPointEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Academic\app\Entities\StudentPoint;

class StudentPointDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StudentPoint $studentPoint,
        public ?int $userId = null,
    ) {}
}
