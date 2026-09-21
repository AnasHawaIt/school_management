<?php

namespace App\Listeners\InspectionPrograms;

use App\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\app\Services\NotificationService;

class NotifyCounselorUnassignedFromInspectionProgram implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        CounselorUnassignedFromInspectionProgram $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->program->user_id],
            title: 'Inspection program assignment removed',
            body: 'You have been removed from an inspection program.',
            type: 'academic',
            data: [
                'entity' => 'inspection_program',
                'action' => 'COUNSELOR_UNASSIGNED_FROM_INSPECTION_PROGRAM',
                'inspection_program_id' => $event->program->id,
                'counselor_id' => $event->counselorId,
                'unassigned_by' => $event->userId,
            ]
        );
    }
}
