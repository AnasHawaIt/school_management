<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Events\MemberEvents\MemberRestored;

class MemberRestoredLogEventListener
{
    public function handle(MemberRestored $event)
    {
        $member = $event->member;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties([
                'member_id' => $member->id,
            ])
            ->log('member.restored');
    }
}
