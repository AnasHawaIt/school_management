<?php

namespace Modules\Transport\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,

            'bus_id'    => [
                'id'   => $this->Bus->id,
                'plate_number' => $this->Bus->plate_number,
                'capacity'  => $this->Bus->capacity,
            ],

            'created_at' => $this->created_at,
        ];
    }
}
