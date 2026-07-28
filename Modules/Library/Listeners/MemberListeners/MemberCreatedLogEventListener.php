<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Events\MemberEvents\MemberCreated;

class MemberCreatedLogEventListener
{
    public function handle(MemberCreated $event)
    {
        $member = $event->member;

        activity()
            ->causedBy($event->userId)
            ->performedOn($member)
            ->withProperties([
                'member_id' => $member->id,
            ])
            ->log('member.created');
    }
}
