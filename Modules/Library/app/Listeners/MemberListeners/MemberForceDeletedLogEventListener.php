<?php

namespace Modules\Library\app\Listeners\MemberListeners;

use Modules\Library\app\Events\MemberEvents\MemberForceDeleted;

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
