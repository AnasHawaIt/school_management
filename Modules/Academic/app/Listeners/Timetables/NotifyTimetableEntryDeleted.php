<?php
namespace Modules\Academic\app\Listeners\Timetables;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryDeleted;
use Modules\Notifications\app\Services\NotificationService;

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
                'timetable_id' => $event->timetable->id,
                'section_id' => $event->timetable->section_id,
                'teacher_id' => $event->timetable->teacher_id,
                'subject_id' => $event->timetable->subject_id,
                'deleted_by' => auth()->id(),
            ]
        );
    }
}
