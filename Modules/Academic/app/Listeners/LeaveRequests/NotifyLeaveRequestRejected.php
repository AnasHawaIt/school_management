<?php

namespace Modules\Academic\app\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\LeaveRequests\LeaveRequestRejected;
use Modules\Notifications\app\Services\NotificationService;

class NotifyLeaveRequestRejected implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        LeaveRequestRejected $event
    ): void {
        $event->request->loadMissing('requestable.user');

        $user = $event->request->requestable?->user;

        if (!$user) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: [$user->id],
            title: 'Leave request rejected',
            body: 'Your leave request has been rejected.',
            type: 'attendance',
            data: [
                'entity' => 'leave_request',
                'action' => 'LEAVE_REQUEST_REJECTED',
                'leave_request_id' => $event->request->id,
                'requestable_type' => $event->request->requestable_type,
                'requestable_id' => $event->request->requestable_id,
                'reviewer_id' => $event->reviewerId,
                'notes' => $event->notes,
            ]
        );
    }
}
