<?php

namespace Modules\Library\Listeners\MemberListeners;

use Modules\Library\Entities\EventLog;
use Modules\Library\Events\MemberEvents\MemberCreated;

class MemberCreatedLogEventListener
{
    public function handle(MemberCreated $event)
    {
        EventLog::create([
            'user_id' => $event->userId,
            'event_type' => 'Member Created',
            'data' =>$event->member
        ]);
    }
}
