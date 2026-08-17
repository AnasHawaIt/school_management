<?php


namespace Modules\Examination\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Examination\Entities\Exam;

class ExamUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Exam $exam,
        public ?int $causedBy = null
    )
    {
    }
}
