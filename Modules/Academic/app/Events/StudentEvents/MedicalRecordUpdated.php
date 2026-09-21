<?php
namespace Modules\Academic\app\Events\StudentEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Student;

class MedicalRecordUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Student $student,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
