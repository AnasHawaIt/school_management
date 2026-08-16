<?php
namespace Modules\Finance\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'student_id'       => $this->student_id,
            'fee_structure_id' => $this->fee_structure_id,
            'academic_year_id' => $this->academic_year_id,
            'original_amount'  => $this->original_amount,
            'discount_amount'  => $this->discount_amount,
            'net_amount'       => $this->net_amount,
            'paid_amount'      => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'status'           => $this->status,
            'due_date'         => $this->due_date?->format('Y-m-d'),
            'notes'            => $this->notes,
            'fee_type'         => $this->whenLoaded('feeStructure', fn() =>
                $this->feeStructure->feeType?->name ?? null),
            'discount'         => $this->whenLoaded('discount', fn() => $this->discount?->name),
            'payments'         => PaymentResource::collection($this->whenLoaded('payments')),
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
