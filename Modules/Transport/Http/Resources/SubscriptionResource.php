<?php

namespace Modules\Transport\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'student_id' => [
                'id'   => $this->Student->id,
                'first_name' =>$this->Student->first_name,
                'last_name' =>$this->Student->last_name,
                'gender'   =>$this->Student->gender,
                'address' =>$this->Student->address,
                'phone'  =>$this->Student->phone,

            ],

            'route_id'    => [
                'id'   => $this->Route->id,
                'name'       => $this->Route->name,

                'bus_id'    => [
                    'id'   => $this->Bus->id,
                    'plate_number' => $this->Bus->plate_number,
                    'capacity'  => $this->Bus->capacity,
                ],

            ],

            'start_date' => $this->start_date,
            'end_date'    => $this->end_date,
            'status'      => $this->status,

            'created_at' => $this->created_at,
        ];
    }
}
