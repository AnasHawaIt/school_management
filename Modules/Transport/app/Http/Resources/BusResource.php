<?php

namespace Modules\Transport\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'plate_number' => $this->plate_number,
           // 'status'=>$this->status,
            'capacity'  => $this->capacity,
            'created_at' => $this->created_at,
        ];
    }
}
