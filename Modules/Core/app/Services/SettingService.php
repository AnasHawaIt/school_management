<?php

namespace Modules\Core\app\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Core\app\Contracts\Repositories\SettingRepositoryInterface;
use Modules\Core\app\Contracts\Services\SettingServiceInterface;

class SettingService implements SettingServiceInterface
{
    protected $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getAllSettings(): Collection
    {
        return $this->settingRepository->all();
    }

    public function getSetting(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = $this->settingRepository->getByKey($key);

            if (!$setting) {
                return $default;
            }

            return $this->castValue($setting->value, $setting->type);
        });
    }

    public function setSetting(string $key, $value, string $type = 'string'): void
    {
        $this->settingRepository->setValue($key, $value, $type);
        Cache::forget("setting.{$key}");
    }

    public function hasSetting(string $key): bool
    {
        return $this->settingRepository->hasKey($key);
    }

    public function deleteSetting(string $key): void
    {
        $this->settingRepository->deleteByKey($key);
        Cache::forget("setting.{$key}");
    }

    public function updateMultiple(array $settings): void
    {
        foreach ($settings as $key => $data) {
            $value = $data['value'] ?? $data;
            $type = $data['type'] ?? 'string';

            $this->setSetting($key, $value, $type);
        }
    }

    protected function castValue($value, string $type)
    {
        return match ($type) {
            'boolean', 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $value,
            'float', 'double' => (float) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }
}
