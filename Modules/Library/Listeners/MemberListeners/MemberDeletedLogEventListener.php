<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Events\MemberEvents\MemberDeleted;

class MemberDeletedLogEventListener
{
    public function handle(MemberDeleted $event)
    {
        $member = $event->member;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties([
                'member_id' => $member->id,
            ])
            ->log('member.deleted');
    }
}
