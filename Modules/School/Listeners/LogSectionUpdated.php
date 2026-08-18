<?php

namespace Modules\School\Listeners;

use Modules\School\Events\SectionUpdated;

class LogSectionUpdated
{
    public function handle(
        SectionUpdated $event
    ): void {
        $section = $event->section;

        activity()
            ->performedOn($section)
            ->causedBy(auth()->user())
            ->withProperties([
                'section_id' => $section->id,
                'name' => $section->name,
                'class_id' => $section->class_id,
                'room_number' => $section->room_number,
                'max_students' => $section->max_students,
            ])
            ->log('Section.Updated');
    }
}
