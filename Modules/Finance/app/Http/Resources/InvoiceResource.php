<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'student_id' => $this->student_id,
            'academic_year_id' => $this->academic_year_id,
            'total_amount' => $this->total_amount,
            'discount_amount' => $this->discount_amount,
            'net_amount' => $this->net_amount,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'status' => $this->status,
            'issue_date' => $this->issue_date?->format('Y-m-d'),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'student' => $this->whenLoaded('student', fn () => [
                'id' => $this->student->id,
                'name' => trim(($this->student->user->first_name ?? '') . ' ' . ($this->student->user->last_name ?? '')),
            ]),
            'issued_by' => $this->whenLoaded('issuedBy', fn () => [
                'id' => $this->issuedBy->id,
                'name' => trim(($this->issuedBy->first_name ?? '') . ' ' . ($this->issuedBy->last_name ?? '')),
            ]),
            'items' => InvoiceItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
