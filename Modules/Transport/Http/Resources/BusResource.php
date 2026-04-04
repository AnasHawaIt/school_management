<?php

namespace Modules\Transport\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'plate_number' => $this->plate_number,
            'capacity'  => $this->capacity,
            'created_at' => $this->created_at,
        ];
    }
}
