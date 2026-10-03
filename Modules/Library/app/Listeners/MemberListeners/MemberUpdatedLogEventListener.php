<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Events\MemberEvents\MemberUpdated;

class MemberUpdatedLogEventListener
{
    public function handle(MemberUpdated $event)
    {
        $member = $event->member;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties([
                'member_id' => $member->id,
            ])
            ->log('member.updated');
    }
}
