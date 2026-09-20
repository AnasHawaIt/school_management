<?php

namespace Modules\Library\app\Listeners\MemberListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\MemberEvents\MemberDeleted;
use Modules\Notifications\app\Services\NotificationService;

class MemberDeletedNotificationDatabaseListener  implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(MemberDeleted $event): void
    {
        $member = $event->member;

        $this->notificationService->sendToAll(
            title: ' مغادرة عضو   ',
            body: "تمت مغادرة عضو  ",
            type: 'Library',
            data: [
                'entity' => 'Member',
                'action' => 'Deleted',
                'Member_id' => $member->id,
            ]
        );
    }
}
