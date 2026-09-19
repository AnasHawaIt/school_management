<?php

namespace Modules\Core\app\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Core\app\Contracts\Repositories\SettingRepositoryInterface;
use Modules\Core\app\Contracts\Services\SettingServiceInterface;
use Modules\Core\app\Entities\Setting;
use Modules\Core\app\Events\Setting\SettingCreated;
use Modules\Core\app\Events\Setting\SettingDeleted;
use Modules\Core\app\Events\Setting\SettingUpdated;
use Modules\Core\app\Events\Setting\SettingsUpdated;

class SettingService implements SettingServiceInterface
{
    public function __construct(
        protected SettingRepositoryInterface $settingRepository
    ) {}

    public function getAllSettings(): Collection
    {
        return $this->settingRepository->all();
    }

    public function getSetting(
        string $key,
               $default = null
    ) {
        return Cache::remember(
            "setting.{$key}",
            3600,
            function () use ($key, $default) {
                $setting = $this->settingRepository
                    ->getByKey($key);

                if (!$setting) {
                    return $default;
                }

                return $this->castValue(
                    $setting->value,
                    $setting->type
                );
            }
        );
    }

    public function setSetting(
        string $key,
               $value,
        string $type = 'string',
        ?int $userId = null
    ): void {
        DB::transaction(function () use (
            $key,
            $value,
            $type,
            $userId
        ) {
            $existingSetting = $this->settingRepository
                ->getByKey($key);

            if (!$existingSetting) {
                $setting = $this->settingRepository->setValue(
                    $key,
                    $value,
                    $type
                );

                Cache::forget("setting.{$key}");

                event(new SettingCreated(
                    setting: $setting,
                    userId: $userId,
                ));

                return;
            }

            $oldValues = [
                'value' => $existingSetting->value,
                'type' => $existingSetting->type,
            ];

            $setting = $this->settingRepository->setValue(
                $key,
                $value,
                $type
            );

            Cache::forget("setting.{$key}");

            $newValues = [
                'value' => $setting->value,
                'type' => $setting->type,
            ];

            event(new SettingUpdated(
                setting: $setting,
                userId: $userId,
                oldValues: $oldValues,
                newValues: $newValues,
            ));
        });
    }

    public function hasSetting(string $key): bool
    {
        return $this->settingRepository->hasKey($key);
    }

    public function deleteSetting(
        string $key,
        ?int $userId = null
    ): void {
        DB::transaction(function () use ($key, $userId) {
            $setting = $this->settingRepository
                ->getByKey($key);

            if (!$setting) {
                return;
            }

            $this->settingRepository->deleteByKey($key);

            Cache::forget("setting.{$key}");

            event(new SettingDeleted(
                setting: $setting,
                userId: $userId,
            ));
        });
    }

    public function updateMultiple(
        array $settings,
        ?int $userId = null
    ): void {
        DB::transaction(function () use (
            $settings,
            $userId
        ) {
            $changes = [];

            foreach ($settings as $key => $data) {
                $value = is_array($data)
                    ? ($data['value'] ?? null)
                    : $data;

                $type = is_array($data)
                    ? ($data['type'] ?? 'string')
                    : 'string';

                $existingSetting = $this->settingRepository
                    ->getByKey($key);

                $oldValue = $existingSetting?->value;

                $setting = $this->settingRepository->setValue(
                    $key,
                    $value,
                    $type
                );

                Cache::forget("setting.{$key}");

                $changes[] = [
                    'key' => $key,
                    'old_value' => $oldValue,
                    'new_value' => $setting->value,
                    'type' => $setting->type,
                ];
            }

            if (!empty($changes)) {
                event(new SettingsUpdated(
                    changes: $changes,
                    userId: $userId,
                ));
            }
        });
    }

    protected function castValue(
        $value,
        string $type
    ) {
        return match ($type) {
            'boolean', 'bool' =>
            filter_var(
                $value,
                FILTER_VALIDATE_BOOLEAN
            ),

            'integer', 'int' =>
            (int) $value,

            'float', 'double' =>
            (float) $value,

            'array', 'json' =>
            json_decode($value, true),

            default => $value,
        };
    }
}
