<?php

namespace App\Events\GuardianEvens;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entities\Guardian;

class GuardianUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Guardian $guardian,
        public array $changes = [],
        public ?int $userId = null,
    ) {}
}
