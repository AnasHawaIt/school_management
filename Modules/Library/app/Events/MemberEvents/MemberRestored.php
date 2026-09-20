<?php


namespace Modules\Library\app\Events\MemberEvents;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Member;

class MemberRestored
{
    use Dispatchable, SerializesModels;

    public Member $member;

    public function __construct(Member $member,public ?int $userId = null)
    {
        $this->member = $member;
    }
}
