<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'student_fee_id' => $this->student_fee_id,
            'description' => $this->description,
            'original_amount' => $this->original_amount,
            'discount_amount' => $this->discount_amount,
            'net_amount' => $this->net_amount,
            'student_fee' => $this->whenLoaded('studentFee', fn () => [
                'id' => $this->studentFee->id,
                'fee_type' => $this->studentFee->feeStructure?->feeType?->name,
                'due_date' => $this->studentFee->due_date?->format('Y-m-d'),
            ]),
        ];
    }
}
