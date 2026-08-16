<?php
namespace Modules\Finance\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'payment_number'     => $this->payment_number,
            'student_id'         => $this->student_id,
            'student_fee_id'     => $this->student_fee_id,
            'amount'             => $this->amount,
            'method'             => $this->method,
            'status'             => $this->status,
            'bank_name'          => $this->bank_name,
            'transfer_reference' => $this->transfer_reference,
            'transfer_date'      => $this->transfer_date?->format('Y-m-d'),
            'transaction_id'     => $this->transaction_id,
            'paid_at'            => $this->paid_at?->format('Y-m-d H:i:s'),
            'notes'              => $this->notes,
            'received_by'        => $this->whenLoaded('receivedBy', fn() => [
                'id'   => $this->receivedBy->id,
                'name' => $this->receivedBy->first_name . ' ' . $this->receivedBy->last_name,
            ]),
            'created_at'         => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
