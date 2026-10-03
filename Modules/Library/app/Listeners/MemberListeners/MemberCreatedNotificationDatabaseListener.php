<?php

namespace Modules\Library\Listeners\MemberListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Notifications\Services\NotificationService;

class MemberCreatedNotificationDatabaseListener implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(MemberCreated $event): void
    {
        $member = $event->member;

        $this->notificationService->sendToAll(
            title: ' عضو حديد  ',
            body: "تمت انضمام عضو جديد ",
            type: 'Library',
            data: [
                'entity' => 'Member',
                'action' => 'Create',
                'Member_id' => $member->id,
            ]
        );
    }
}
