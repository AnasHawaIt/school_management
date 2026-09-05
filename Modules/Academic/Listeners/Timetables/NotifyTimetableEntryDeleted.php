<?php

namespace Modules\Academic\Listeners\Timetables;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\Events\TimetableEvents\TimetableEntryDeleted;
use Modules\Notifications\Services\NotificationService;

class NotifyTimetableEntryDeleted implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        TimetableEntryDeleted $event
    ): void {
        if (empty($event->recipientUserIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: array_values(array_unique($event->recipientUserIds)),
            title: 'Timetable entry removed',
            body: 'A timetable entry has been removed.',
            type: 'academic',
            data: [
                'entity' => 'timetable',
                'action' => 'TIMETABLE_ENTRY_DELETED',
                'timetable_id' => $event->timetableId,
                'section_id' => $event->sectionId,
                'teacher_id' => $event->teacherId,
                'subject_id' => $event->subjectId,
                'deleted_by' => $event->userId,
            ]
        );
    }
}
