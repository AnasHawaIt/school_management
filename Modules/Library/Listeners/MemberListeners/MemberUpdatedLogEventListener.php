<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\MemberEvents\MemberUpdated;

class MemberUpdatedLogEventListener
{
    public function handle(MemberUpdated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'Member Updated',
            'data' =>$event->member
        ]);
    }
}
