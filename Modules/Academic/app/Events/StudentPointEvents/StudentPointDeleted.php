<?php


namespace Modules\Academic\app\Events\StudentPointEvents;

use App\Entities\StudentPoint;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentPointDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public StudentPoint $studentPoint,
        public ?int $userId = null,
    ) {}
}
