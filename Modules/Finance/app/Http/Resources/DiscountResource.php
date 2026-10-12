<?php
namespace Modules\Finance\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'name_ar'   => $this->name_ar,
            'type'      => $this->type,
            'value'     => $this->value,
            'reason'    => $this->reason,
            'is_active' => $this->is_active,
            'created_at'=> $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
