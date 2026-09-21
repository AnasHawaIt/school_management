<?php

namespace App\Listeners\InspectionPrograms;

use App\Events\InspectionProgramEvents\CounselorUnassignedFromInspectionProgram;
use App\Events\InspectionProgramEvents\ObservationSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notifications\app\Services\NotificationService;

class NotifyObservationSubmitted implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        ObservationSubmitted $event
    ): void {
        if (empty($event->recipientUserIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: array_values(array_unique($event->recipientUserIds)),
            title: 'New observation submitted',
            body: 'A new observation has been submitted for an inspection program.',
            type: 'academic',
            data: [
                'entity' => 'inspection_observation',
                'action' => 'OBSERVATION_SUBMITTED',
                'inspection_program_id' => $event->program->id,
                'observation_id' => $event->program->observation->id,
                'submitted_by' => auth()->id(),
            ]
        );
    }
}
