<?php

namespace Modules\Academic\app\Listeners\StudentAttendance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Academic\app\Events\StudentAttendance\StudentAttendanceRecorded;
use Modules\Notifications\app\Services\NotificationService;

class NotifyStudentAbsence implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function handle(
        StudentAttendanceRecorded $event
    ): void {
        if ($event->attendance->status !== 'A') {
            return;
        }

        $event->attendance->loadMissing('student.user', 'student.guardians.user');

        $userIds = [];

        if ($event->attendance->student?->user_id) {
            $userIds[] = $event->attendance->student->user_id;
        }

        foreach ($event->attendance->student?->guardians ?? [] as $guardian) {
            if ($guardian->user_id) {
                $userIds[] = $guardian->user_id;
            }
        }

        $userIds = array_values(array_unique($userIds));

        if (empty($userIds)) {
            return;
        }

        $this->notificationService->sendToUsers(
            userIds: $userIds,
            title: 'Student absence',
            body: 'The student has been marked absent today.',
            type: 'attendance',
            data: [
                'entity' => 'student_attendance',
                'action' => 'STUDENT_ABSENT',
                'attendance_id' => $event->attendance->id,
                'student_id' => $event->attendance->student_id,
                'section_id' => $event->attendance->section_id,
                'date' => $event->attendance->date,
                'status' => $event->attendance->status,
                'recorded_by' => $event->userId,
            ]
        );
    }
}
