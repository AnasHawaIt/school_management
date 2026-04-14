<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\MemberEvents\MemberDeleted;

class MemberDeletedLogEventListener
{
    public function handle(MemberDeleted $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'Member Deleted',
            'data' =>$event->member
        ]);
    }
}
