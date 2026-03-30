<?php

namespace Modules\Core\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;

interface SettingServiceInterface
{
    public function getAllSettings(): Collection;

    public function getSetting(string $key, $default = null);

    public function setSetting(string $key, $value, string $type = 'string'): void;

    public function hasSetting(string $key): bool;

    public function deleteSetting(string $key): void;

    public function updateMultiple(array $settings): void;
}
