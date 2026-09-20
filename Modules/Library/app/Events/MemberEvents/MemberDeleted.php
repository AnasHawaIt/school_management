<?php

namespace Modules\Library\app\Events\MemberEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Member;

class MemberDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Member $member,
        public ?int $userId = null,
        public ?string $socketId = null
    ) {}
}


