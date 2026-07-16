<?php

namespace Modules\Library\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'start_date'       => $this->start_date,
            'membership_number'      => $this->membership_number,
            'student'=>[
                'student_id'=>$this->student_id,
                'academic_year_id'=>$this->academic_year_id,
                'status'=>$this->status,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
