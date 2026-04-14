<?php

namespace Modules\Transport\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RouteStopResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'stop_name' => $this->stop_name,

//            'route_id'    => [
//                'id'   => $this->Route->id,
//                'name'       => $this->Route->name,
//
//                'bus_id'    => [
//                    'id'   => $this->Bus->id,
//                    'plate_number' => $this->Bus->plate_number,
//                    'capacity'  => $this->Bus->capacity,
//                ],
//
//            ],

            'sequence'     => $this->sequence,
            'created_at' => $this->created_at,
        ];
    }
}
