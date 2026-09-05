<?php

namespace Modules\Academic\Listeners\InspectionPrograms;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\InspectionProgramEvents\CounselorAssignedToInspectionProgram;
use Modules\Notifications\Services\NotificationService;

class NotifyCounselorAssignedToInspectionProgram implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        CounselorAssignedToInspectionProgram $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->counselor->user_id],
            title: 'Inspection program assigned',
            body: 'You have been assigned to an inspection program.',
            type: 'academic',
            data: [
                'entity' => 'inspection_program',
                'action' => 'COUNSELOR_ASSIGNED_TO_INSPECTION_PROGRAM',
                'inspection_program_id' => $event->program->id,
                'counselor_id' => $event->counselor->id,
                'role' => $event->role,
                'assigned_by' => $event->userId,
            ]
        );
    }
}
