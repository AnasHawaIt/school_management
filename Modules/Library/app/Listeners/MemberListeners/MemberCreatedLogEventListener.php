<?php

namespace Modules\Library\app\Listeners\MemberListeners;

use Modules\Library\app\Events\MemberEvents\MemberCreated;

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
