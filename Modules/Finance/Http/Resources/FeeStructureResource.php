<?php
namespace Modules\Finance\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeStructureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'fee_type_id'      => $this->fee_type_id,
            'academic_year_id' => $this->academic_year_id,
            'grade_id'         => $this->grade_id,
            'class_id'         => $this->class_id,
            'amount'           => $this->amount,
            'frequency'        => $this->frequency,
            'due_date'         => $this->due_date?->format('Y-m-d'),
            'is_active'        => $this->is_active,
            'fee_type'         => $this->whenLoaded('feeType', fn() => new FeeTypeResource($this->feeType)),
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
