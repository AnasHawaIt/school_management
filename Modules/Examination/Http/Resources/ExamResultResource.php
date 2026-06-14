<?php

namespace Modules\Examination\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'marks_obtained' => $this->marks_obtained,
            'is_absent'      => $this->is_absent,
            'is_passed'      => $this->is_passed,
            'percentage'     => $this->percentage,
            'remarks'        => $this->remarks,

            // الاسم عبر student->user
            'student' => $this->whenLoaded('student', fn() => [
                'id'         => $this->student->id,
                'student_id' => $this->student->student_id,
                'full_name'  => $this->student->user->first_name . ' ' . $this->student->user->last_name,
                'avatar'     => $this->student->user->avatar
                    ? asset('storage/' . $this->student->user->avatar)
                    : null,
            ]),

            'exam' => $this->whenLoaded('exam', fn() => [
                'id'          => $this->exam->id,
                'name'        => $this->exam->name,
                'total_marks' => $this->exam->total_marks,
                'pass_marks'  => $this->exam->pass_marks,
                'subject'     => $this->exam->subject?->name,
            ]),

            'entered_by' => $this->whenLoaded('enteredBy', fn() => [
                'id'        => $this->enteredBy->id,
                'full_name' => $this->enteredBy->first_name . ' ' . $this->enteredBy->last_name,
            ]),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
