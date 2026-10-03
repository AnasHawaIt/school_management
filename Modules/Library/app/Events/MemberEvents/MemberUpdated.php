<?php

namespace Modules\Library\Events\MemberEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Member;

class MemberUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
       public Member $member,
        public ?int $userId = null,
        public array $changes = []
    ) {}
}

