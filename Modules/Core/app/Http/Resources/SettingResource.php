<?php

namespace Modules\Core\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->getCastedValue(),
            'type' => $this->type,
            'description' => $this->description,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    protected function getCastedValue()
    {
        return match ($this->type) {
            'boolean', 'bool' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $this->value,
            'float', 'double' => (float) $this->value,
            'array', 'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }
}
