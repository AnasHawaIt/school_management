<?php

namespace Modules\Library\Repositories\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;

class BookRepositoryInterface extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student' => [
                'id' => $this->student->id,
                'name' => $this->student->name,
            ],
            'route' => [
                'id' => $this->route->id,
                'name' => $this->route->name,
            ],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ];
    }
}
