<?php

namespace Modules\Library\Events\MemberEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Member;

class MemberDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Member $member,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}


