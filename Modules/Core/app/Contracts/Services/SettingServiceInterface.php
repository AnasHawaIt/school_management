<?php

namespace Modules\Core\app\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;

interface SettingServiceInterface
{
    public function getAllSettings(): Collection;

    public function getSetting(
        string $key,
               $default = null
    );

    public function setSetting(
        string $key,
               $value,
        string $type = 'string',
        ?int $userId = null
    ): void;

    public function hasSetting(string $key): bool;

    public function deleteSetting(
        string $key,
        ?int $userId = null
    ): void;

    public function updateMultiple(
        array $settings,
        ?int $userId = null
    ): void;
}
