<?php

namespace Modules\Academic\app\Listeners\Counselors;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\CounselorEvents\CounselorSectionUnassigned;
use Modules\Notifications\app\Services\NotificationService;

class NotifyCounselorSectionUnassigned implements ShouldQueue
{
    public function __construct(
    protected NotificationService $notificationService
) {
}

    public function handle(
    CounselorSectionUnassigned $event
): void {
    $this->notificationService->sendToUsers(
        userIds: [$event->counselor->user_id],
        title: 'Section assignment removed',
        body: 'Your assignment to a section has been removed.',
        type: 'counselor_section_unassigned',
        data: [
            'entity' => 'counselor_section',
            'action' => 'SECTION_UNASSIGNED',
            'counselor_id' => $event->counselor->id,
            'section_id' => $event->sectionId,
            'academic_year_id' => $event->academicYearId,
            'unassigned_by' => $event->userId,
        ]
    );
}
}
