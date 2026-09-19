<?php

namespace Modules\Core\app\Repositories;

use App\Repositories\BaseRepository;
use Modules\Core\app\Contracts\Repositories\SettingRepositoryInterface;
use Modules\Core\app\Entities\Setting;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    public function getByKey(string $key): ?Setting
    {
        return $this->model
            ->byKey($key)
            ->first();
    }

    public function setValue(
        string $key,
               $value,
        string $type = 'string'
    ): Setting {
        return $this->model->updateOrCreate(
            ['key' => $key],
            [
                'value' => $this->prepareValue($value, $type),
                'type' => $type,
            ]
        );
    }

    public function hasKey(string $key): bool
    {
        return $this->model
            ->where('key', $key)
            ->exists();
    }

    public function deleteByKey(string $key): void
    {
        $this->model
            ->where('key', $key)
            ->delete();
    }

    protected function prepareValue(
        $value,
        string $type
    ): string {
        return match ($type) {
            'boolean', 'bool' => $value ? '1' : '0',

            'array', 'json' => json_encode(
                $value,
                JSON_UNESCAPED_UNICODE
            ),

            default => (string) $value,
        };
    }
}
