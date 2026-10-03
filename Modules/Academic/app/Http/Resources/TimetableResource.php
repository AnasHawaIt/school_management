<?php

namespace Modules\Academic\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimetableResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'day_of_week'   => $this->day_of_week,
            'period_number' => $this->period_number,
            'start_time'    => $this->start_time?->format('H:i'),
            'end_time'      => $this->end_time?->format('H:i'),
            'room_number'   => $this->room_number,
            'status'        => $this->status,
            'subject'       => $this->whenLoaded('subject', fn() => [
                'id'   => $this->subject->id,
                'name' => $this->subject->name,
                'code' => $this->subject->code,
            ]),
            'teacher'       => $this->whenLoaded('teacher', fn() => [
                'id'        => $this->teacher->id,
                'full_name' => $this->teacher->full_name,
            ]),
            'section'       => $this->whenLoaded('section', fn() => [
                'id'    => $this->section->id,
                'name'  => $this->section->name,
                'class' => $this->section->class?->name,
                'grade' => $this->section->class?->grade?->name,
            ]),
            'semester'      => $this->whenLoaded('semester', fn() => [
                'id'   => $this->semester->id,
                'name' => $this->semester->name,
            ]),
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
