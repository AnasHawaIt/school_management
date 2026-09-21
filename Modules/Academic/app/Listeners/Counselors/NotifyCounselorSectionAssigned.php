<?php

namespace Modules\Academic\app\Listeners\Counselors;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionAssigned;
use Modules\Notifications\app\Services\NotificationService;

class NotifyCounselorSectionAssigned implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        CounselorSectionAssigned $event
    ): void {
        $this->notificationService->sendToUsers(
            userIds: [$event->counselor->user_id],
            title: 'Section assigned',
            body: 'A new section has been assigned to you.',
            type: 'counselor_section_assigned',
            data: [
                'entity' => 'counselor_section',
                'action' => 'SECTION_ASSIGNED',
                'counselor_id' => $event->counselor->id,
                'section_id' => $event->sectionId,
                'academic_year_id' => $event->academicYearId,
                'assigned_by' => $event->userId,
            ]
        );
    }
}
