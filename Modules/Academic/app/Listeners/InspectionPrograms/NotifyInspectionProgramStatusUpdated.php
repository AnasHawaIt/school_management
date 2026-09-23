<?php

namespace Modules\Academic\app\Listeners\InspectionPrograms;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramStatusUpdated;
use Modules\Notifications\app\Services\NotificationService;

class NotifyInspectionProgramStatusUpdated implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        InspectionProgramStatusUpdated $event
    ): void {
        if (empty($event->recipientUserIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: array_values(array_unique($event->recipientUserIds)),
            title: 'Inspection program status updated',
            body: 'The status of an inspection program has been updated.',
            type: 'academic',
            data: [
                'entity' => 'inspection_program',
                'action' => 'INSPECTION_PROGRAM_STATUS_UPDATED',
                'inspection_program_id' => $event->program->id,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'updated_by' => $event->userId,
            ]
        );
    }
}
