<?php

namespace Modules\Academic\Listeners\Guardians\LogGuardians;

use Modules\Academic\Events\GuardianEvens\StudentDetachedFromGuardian;

class LogStudentDetachedFromGuardian
{
    public function handle(StudentDetachedFromGuardian $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->guardian)
            ->withProperties([
                'student_id' => $event->studentId,
            ])
            ->log('Student detached from guardian');
    }
}
