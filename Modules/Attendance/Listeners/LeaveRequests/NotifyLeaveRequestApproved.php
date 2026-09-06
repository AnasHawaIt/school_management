<?php


namespace Modules\Attendance\Listeners\LeaveRequests;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Attendance\ Events\LeaveRequests\LeaveRequestApproved;
use Modules\Notifications\Services\NotificationService;

class NotifyLeaveRequestApproved implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(
        LeaveRequestApproved $event
    ): void
    {
        $event->request->loadMissing('requestable.user');

        $user = $event->request->requestable?->user;

        if (!$user) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: [$user->id],
            title: 'Leave request approved',
            body: 'Your leave request has been approved.',
            type: 'attendance',
            data: [
                'entity' => 'leave_request',
                'action' => 'LEAVE_REQUEST_APPROVED',
                'leave_request_id' => $event->request->id,
                'requestable_type' => $event->request->requestable_type,
                'requestable_id' => $event->request->requestable_id,
                'reviewer_id' => $event->reviewerId,
                'notes' => $event->notes,
            ]
        );
    }
}
