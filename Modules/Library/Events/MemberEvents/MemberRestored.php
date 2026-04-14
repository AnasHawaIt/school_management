<?php


namespace Modules\Library\Events\MemberEvents;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Member;

class MemberRestored
{
    use Dispatchable, SerializesModels;

    public Member $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }
}
