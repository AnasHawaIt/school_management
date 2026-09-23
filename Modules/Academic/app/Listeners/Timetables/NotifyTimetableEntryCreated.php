<?php
namespace Modules\Academic\app\Listeners\Timetables;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\TimetableEvents\TimetableEntryCreated;
use Modules\Notifications\app\Services\NotificationService;

class NotifyTimetableEntryCreated implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        TimetableEntryCreated $event
    ): void {
        if (empty($event->recipientUserIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: array_values(array_unique($event->recipientUserIds)),
            title: 'New timetable entry',
            body: 'A new timetable entry has been added.',
            type: 'academic',
            data: [
                'entity' => 'timetable',
                'action' => 'TIMETABLE_ENTRY_CREATED',
                'timetable_id' => $event->timetable->id,
                'section_id' => $event->timetable->section_id,
                'teacher_id' => $event->timetable->teacher_id,
                'subject_id' => $event->timetable->subject_id,
                'created_by' => $event->userId,
            ]
        );
    }
}
