<?php

namespace App\Listeners\InspectionPrograms\LogInspectionPrograms;

use App\Events\InspectionProgramEvents\InspectionProgramCreated;

class LogInspectionProgramCreated
{
    public function handle(InspectionProgramCreated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($event->program)
            ->withProperties([
                'inspection_program_id' => $event->program->id,
                'section_id'            => $event->program->section_id,
                'semester_id'           => $event->program->semester_id,
                'academic_year_id'      => $event->program->academic_year_id ?? null,
            ])
            ->log('Inspection program created');
    }
}
