<?php

namespace Modules\Examination\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'name_ar'      => $this->name_ar,
            'exam_date'    => $this->exam_date?->format('Y-m-d'),
            'start_time'   => $this->start_time,
            'end_time'     => $this->end_time,
            'room'         => $this->room,
            'total_marks'  => $this->total_marks,
            'pass_marks'   => $this->pass_marks,
            'status'       => $this->status,
            'instructions' => $this->instructions,

            'exam_type' => $this->whenLoaded('examType', fn() => [
                'id'      => $this->examType->id,
                'name'    => $this->examType->name,
                'name_ar' => $this->examType->name_ar,
                'weight'  => $this->examType->weight,
            ]),

            'subject' => $this->whenLoaded('subject', fn() => [
                'id'   => $this->subject->id,
                'name' => $this->subject->name,
                'code' => $this->subject->code,
            ]),

            'section' => $this->whenLoaded('section', fn() => [
                'id'    => $this->section->id,
                'name'  => $this->section->name,
                'class' => $this->section->class?->name,
                'grade' => $this->section->class?->grade?->name,
            ]),

            // الاسم عبر teacher->user
            'teacher' => $this->whenLoaded('teacher', fn() => [
                'id'          => $this->teacher->id,
                'employee_id' => $this->teacher->employee_id,
                'full_name'   => $this->teacher->user->first_name . ' ' . $this->teacher->user->last_name,
            ]),

            'semester' => $this->whenLoaded('semester', fn() => [
                'id'   => $this->semester->id,
                'name' => $this->semester->name,
            ]),

            'results_count' => $this->whenLoaded('results', fn() => $this->results->count()),
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
