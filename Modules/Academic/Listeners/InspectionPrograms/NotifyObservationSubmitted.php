<?php

namespace Modules\Academic\Listeners\InspectionPrograms;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\InspectionProgramEvents\ObservationSubmitted;
use Modules\Notifications\Services\NotificationService;

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
                'observation_id' => $event->observation->id,
                'submitted_by' => $event->userId,
            ]
        );
    }
}
