<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Events\MemberEvents\MemberForceDeleted;

class MemberForceDeletedLogEventListener
{
    public function handle(MemberForceDeleted $event)
    {
        $member = $event->member;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties([
                'member_id' => $member->id,
            ])
            ->log('member.forceDeleted');
    }
}
