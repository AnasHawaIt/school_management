<?php

namespace Modules\Academic\app\Listeners\InspectionPrograms;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\InspectionProgramEvents\InspectionProgramSetCurrent;
use Modules\Notifications\app\Services\NotificationService;

class NotifyInspectionProgramSetCurrent implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        InspectionProgramSetCurrent $event
    ): void {
        if (empty($event->recipientUserIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: array_values(array_unique($event->recipientUserIds)),
            title: 'Current inspection program updated',
            body: 'An inspection program has been set as the current program.',
            type: 'academic',
            data: [
                'entity' => 'inspection_program',
                'action' => 'INSPECTION_PROGRAM_SET_CURRENT',
                'inspection_program_id' => $event->program->id,
                'set_by' => $event->userId,
            ]
        );
    }
}
