<?php

namespace Modules\Academic\Listeners\Guardians\LogGuardians;

use Modules\Academic\Events\GuardianEvens\StudentAttachedToGuardian;

class LogStudentAttachedToGuardian
{
    public function handle(StudentAttachedToGuardian $event): void
    {
        activity()
            ->causedBy($event->userId)
            ->performedOn($event->guardian)
            ->withProperties([
                'student_id' => $event->studentId,
                'relationship' => $event->pivotData['relationship'] ?? null,
                'is_primary_contact' =>
                    $event->pivotData['is_primary_contact'] ?? false,
                'can_pickup' =>
                    $event->pivotData['can_pickup'] ?? true,
            ])
            ->log('Student attached to guardian');
    }
}
