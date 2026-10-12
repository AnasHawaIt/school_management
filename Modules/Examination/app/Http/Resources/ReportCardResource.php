<?php

namespace Modules\Examination\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportCardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'total_marks'     => $this->total_marks,
            'obtained_marks'  => $this->obtained_marks,
            'percentage'      => $this->percentage,
            'grade'           => $this->grade,
            'rank'            => $this->rank,
            'result'          => $this->result,
            'teacher_remarks' => $this->teacher_remarks,
            'is_published'    => $this->is_published,

            // الاسم عبر student->user
            'student' => $this->whenLoaded('student', fn() => [
                'id'         => $this->student->id,
                'student_id' => $this->student->student_id,
                'full_name'  => $this->student->user->first_name . ' ' . $this->student->user->last_name,
                'avatar'     => $this->student->user->avatar
                    ? asset('storage/' . $this->student->user->avatar)
                    : null,
            ]),

            'section' => $this->whenLoaded('section', fn() => [
                'id'    => $this->section->id,
                'name'  => $this->section->name,
                'class' => $this->section->class?->name,
                'grade' => $this->section->class?->grade?->name,
            ]),

            'semester' => $this->whenLoaded('semester', fn() => [
                'id'   => $this->semester->id,
                'name' => $this->semester->name,
            ]),

            'academic_year' => $this->whenLoaded('academicYear', fn() => [
                'id'   => $this->academicYear->id,
                'name' => $this->academicYear->name,
            ]),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
