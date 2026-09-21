<?php


namespace App\Events\StudentPointEvents;

use App\Entities\StudentPoint;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentPointsBulkGiven
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public array $studentIds,
        public int $pointCategoryId,
        public int $points,
        public ?int $userId = null,
    ) {}
}
