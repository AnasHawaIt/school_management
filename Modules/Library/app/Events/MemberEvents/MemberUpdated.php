<?php

namespace Modules\Library\app\Events\MemberEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Member;

class MemberUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
       public Member $member,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

