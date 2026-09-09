<?php

namespace Modules\Library\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FineResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'paid_at' => $this->paid_at,
            'waived_at' => $this->waived_at,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        ];
    }
}
