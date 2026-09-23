<?php
namespace Modules\Academic\app\Listeners\Timetables;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryUpdated;
use Modules\Notifications\app\Services\NotificationService;

class NotifyTimetableEntryUpdated implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        TimetableEntryUpdated $event
    ): void {
        if (empty($event->recipientUserIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: array_values(array_unique($event->recipientUserIds)),
            title: 'Timetable updated',
            body: 'A timetable entry has been updated.',
            type: 'academic',
            data: [
                'entity' => 'timetable',
                'action' => 'TIMETABLE_ENTRY_UPDATED',
                'timetable_id' => $event->timetable->id,
                'section_id' => $event->timetable->section_id,
                'teacher_id' => $event->timetable->teacher_id,
                'subject_id' => $event->timetable->subject_id,
                'changes' => $event->changes,
                'updated_by' => auth()->id(),
            ]
        );
    }
}
